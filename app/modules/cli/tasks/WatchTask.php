<?php
namespace PhalconTryout\Modules\Cli\Tasks;

use Phalcon\Security\Random;
use PhalconTryout\Models\Programs;
use PhalconTryout\Models\Recordings;
use SoapClient;

/**
 * @property \Phalcon\Config config
 */
class WatchTask extends \Phalcon\Cli\Task
{
    /** Log reading refresh time */
    const REFRESH_TIME = 5;

    /**
     * @var SoapClient $soap_client
     */
    protected $soap_client;
    /**
     * @var Random $random
     */
    protected $random;

    public function mainAction()
    {
        if (!$this->checkExtensions()) {
            echo 'Please check required extensions.';
            return;
        }
        if (!$this->checkVideoApp($this->getVideoServiceConfig('video_app'))) {
            echo 'Please check required video app.';
            return;
        }
        $this->setSoapClient(new SoapClient($this->getVideoServiceConfig('wsdl_url')));
        $this->setRandom(new Random());

        try {
            $this->getVideo();
        } catch (\Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * @return SoapClient
     */
    public function getSoapClient(): SoapClient
    {
        return $this->soap_client;
    }

    /**
     * @param SoapClient $soap_client
     * @return WatchTask
     */
    public function setSoapClient(SoapClient $soap_client): WatchTask
    {
        $this->soap_client = $soap_client;
        return $this;
    }

    /**
     * @return Random
     */
    public function getRandom(): Random
    {
        return $this->random;
    }

    /**
     * @param Random $random
     * @return WatchTask
     */
    public function setRandom(Random $random): WatchTask
    {
        $this->random = $random;
        return $this;
    }

    /**
     * Get Config data from config
     * @param string|null $config
     * @return string
     */
    protected function getVideoServiceConfig(string $config = null): string
    {
        if ($config === null) {
            $this->config->get('video_service');
        }
        return $this->config->get('video_service')->get($config);
    }

    /**
     * Get WSDL Url from config
     * @param string $time_start
     * @param string $time_end
     * @return array
     */
    protected function getSchedule(string $time_start, string $time_end): array
    {
        $items = [];
        $client = $this->getSoapClient();
        $params = [
            'getInfoByTime' => [
                'start_time'    => $time_start,
                'end_time'      => $time_end
            ]
        ];
        $response = $client->__soapCall('getInfoByTime', $params);
        if (is_soap_fault($response)) {
            return $items;
        }
        foreach (simplexml_load_string($response->{'getInfoByTimeResult'}) as $item) {
            /** @var \SimpleXMLElement $item */
            $items[] = $item;
        }

        return $items;
    }

    /**
     * Infinite loop of parsing video from stream
     * @throws \Phalcon\Security\Exception
     */
    protected function getVideo()
    {
        $names = $this->generateVideoName();
        $command = implode(' ', [
            $this->getVideoServiceConfig('video_app'),
            '-i ' . $this->getVideoServiceConfig('video_source'),
            '-c copy',
            '-f mp4',
            '-t ' . $this->getVideoServiceConfig('video_length'),
            $names['file'],
            '1> ' . $names['log'],
            '2>&1',
        ]);

        if (0 === stripos(PHP_OS, 'WIN')) {
            pclose(popen('start /B ' . $command, 'r'));
        } else {
            pclose(popen($command . ' &', 'r'));
        }

        $this->createNewRecording($names['file']);
        if ($this->checkLog($names['log'])) {
            $this->getVideo();
        }
    }

    /**
     * @param string $name
     * @return Recordings|null
     */
    protected function createNewRecording(string $name): Recordings
    {
        $length = $this->getVideoServiceConfig('video_length');
        $path = str_replace(BASE_PATH . '/public_html', '', $name);

        $time_start = date('Y-m-d H:i:s', time());
        $time_end = date('Y-m-d H:i:s', strtotime("+$length seconds"));

        $new_recording = new Recordings();
        $new_recording->setTimeStart($time_start)
            ->setTimeEnd($time_end)
            ->setPath($path);

        $programs_soap = $this->getSchedule($time_start, $time_end);
        $programs = [];
        foreach ($programs_soap as $program) {
            $new_program = new Programs();
            $new_program->setTitle($program);
            $programs[] = $new_program;
        }

        $new_recording->programs = $programs;

        if ($new_recording->save() === false) {
            echo 'Error saving recording';
            $messages = $new_recording->getMessages();
            foreach ($messages as $message) {
                echo $message;
            }
            return null;
        }

        echo 'New recording added to DB';
        return $new_recording;
    }

    /**
     * @param string $title
     * @return Programs|null
     */
    protected function createNewProgram(string $title): Programs
    {
        $new_program = new Programs();
        $new_program->setTitle($title);

        if ($new_program->save() === false) {
            echo 'Error saving recording';
            $messages = $new_program->getMessages();
            foreach ($messages as $message) {
                echo $message;
            }
            return null;
        }

        echo 'New program added to DB';
        return null;
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function checkLog(string $name): bool
    {
        $last_position = 0;
        $stop_checking = false;
        do {
            sleep(5);
            clearstatcache(false, $name);
            $length = filesize($name);
            if ($length < $last_position) {
                $last_position = $length;
            } elseif ($length > $last_position) {
                $file = fopen($name, 'rb');
                if ($file === false) {
                    die();
                }
                $seconds = 0;
                fseek($file, $last_position);
                while (!feof($file)) {
                    $buffer = fread($file, 4096);
                    $seconds = $this->parseLogString($buffer);
                }
                $last_position = ftell($file);
                fclose($file);
                if ($seconds > ($this->getVideoServiceConfig('video_length') - $this->getVideoServiceConfig('new_video_offset'))) {
                    $stop_checking = true;
                }
            }
        } while ($stop_checking === false);

        return true;
    }

    /**
     * @param string $string
     * @return int
     */
    protected function parseLogString(string $string): int
    {
        preg_match_all('/Time: [(.*?)] fps/', $string, $matches);
        $time_string = end($matches[1]);
        if ($time_string !== false) {
            return strtotime($time_string) - strtotime('today');
        }

        return 0;
    }

    /**
     * @return array
     * @throws \Phalcon\Security\Exception
     */
    protected function generateVideoName(): array
    {
        $path_part = $this->getVideoServiceConfig('output_path') . 'capture_';

        do {
            $rand = $this->getRandom()->hex(8);
            $name_file = $path_part . $rand . '.mp4';
            $name_log = $path_part . $rand . '.log';
        } while (file_exists($name_file) || file_exists($name_log));

        return [
            'file'  => $name_file,
            'log'   => $name_log,
        ];
    }

    /**
     * Check video app exist
     * @param string $path
     * @return bool
     */
    protected function checkVideoApp(string $path): bool
    {
        if (0 === stripos(PHP_OS, 'WIN')) {
            $result = shell_exec("where $path");
            $exists = $result !== null;
        } else {
            $result = shell_exec("which $path");
            $exists = !empty($result);
        }

        return $exists;
    }

    /**
     * Check extension loaded
     * @return bool
     */
    protected function checkExtensions(): bool
    {
        return extension_loaded('soap');
    }
}
