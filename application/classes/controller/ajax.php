<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

    // Response format
    public $format;
    
    // Response data
    public $template;
    
    public $auto_render = true;
    
    /**
     * Initialize properties before running the controller methods (actions),
     * so they are available to our action.
     */
    public function before()
    {
        parent::before();

        if (!Request::current()->is_ajax() AND Kohana::$environment !== Kohana::DEVELOPMENT)
        {
            header('Content-type: application/json');
            echo json_encode(array(
                'success' => false,
                'message' => 'Not authotized request',
                'total' => 0
            ));
            exit;
        }
        
        $this->format = $this->request->param('format');

        $this->template = array(
            'success' => false,
            'data' => null,
            'message' => '',
            'total' => null
        );

        switch ($this->format) {
            case 'json':
            case 'jsonp':
                $this->response->headers('Content-Type', 'application/json');
                break;
            // TODO make xml view output
            case 'xml':
                $this->response->headers('Content-Type', 'text/xml');
                break;
        }
    }

    public function after() {
        
        if ($this->auto_render) {
            switch ($this->format) {
                // jsonp is a plain or simple json without messages
                case 'jsonp':
                    $this->response->body(json_encode($this->template['data']));
                    break;
                case 'json':
                    if ($this->template['success'] === false) {
                        $this->response->body(json_encode(array(
                            'success' => $this->template['success'],
                            'total' => 0,
                            'message' => $this->template['message']
                        )));
                    } else {
                        if (is_array($this->template['data']) AND $this->template['total'] === null) {
                            $this->template['total'] = count($this->template['data']);
                        }
                        $response = array(
                            'success' => $this->template['success'],
                            'total' => (int) $this->template['total'],
                            'data' => $this->template['data'],
                            'message' => $this->template['message']
                        );
                        if (isset($this->template['params']))
                            $response['params'] = $this->template['params'];
                        $this->response->body(json_encode($response));
                    }
                    break;
                // TODO make xml view output
                case 'xml':
                    $this->response->body($this->template['data']);
                    break;
                case 'html':
                default:
                    $this->response->body($this->template['data']);
                    break;
            }
        }

        parent::after();
    }

}
