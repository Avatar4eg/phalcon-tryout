<?php
namespace PhalconTryout\Modules\Frontend\Controllers;

use Phalcon\Paginator\Adapter\Model as Paginator;
use PhalconTryout\Models\Recordings;

class RecordingsController extends ControllerBase
{
    const ALLOWED_SORT_FIELDS = [
        'id', 'time_start', 'time_end'
    ];

    /**
     * Index action
     */
    public function indexAction()
    {
        $params = [];

        $order = $this->request->getQuery('sort', 'string', 'id');
        if (in_array(str_replace('-', '', $order), self::ALLOWED_SORT_FIELDS, true)) {
            $params['order'] = $order;
        }

        $recordings = Recordings::find($params);

        $paginator = new Paginator([
            'data'  => $recordings,
            'limit' => 25,
            'page'  => $this->request->getQuery('page', 'int', 1),
        ]);

        $this->view->sort_data = $order !== 'id' ? $order : null;
        $this->view->page = $paginator->getPaginate();
    }
}
