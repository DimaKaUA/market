<?php 

include_once ROOT . '/models/Order.php';
include_once ROOT . '/models/Category.php';
include_once ROOT . '/models/Item.php';
include_once ROOT . '/controllers/AuthController.php';

/**
 * Class OrderController
 */
class OrderController extends Controller {
    
    /**
     * Displays a listing of the orders
     *
     * @return boolean
     */
    public function indexAction()
    {

        AuthController::checkAuth();

        $orderModel = new Order();
        $allOrders = $orderModel->getList();
        
        $itemModel = new Item();

        for ($i=0; $i < count($allOrders); $i++) { 
            $allOrders[$i]['items'] = unserialize($allOrders[$i]['items']);
            $itemsIds = array_keys($allOrders[$i]['items']);
            $allOrders[$i]['items'] = $itemModel->getListByIds($itemsIds);
            $allOrders[$i]['status'] = Order::getStatusText($allOrders[$i]['status']);
        }
              
        $this->view->generate('orders/index_view.twig.html', $allOrders);
        return true;
    }

    /**
     * Displays requested order
     * 
     * @return boolean
     */
    public function showAction($id)
    {

        AuthController::checkAuth();

        if ($id) {
            $orderModel = new Order();
            $order = $orderModel->getRecordById($id);

            $itemModel = new Item();

            $order['items'] = unserialize($order['items']);

            $items = array();

            foreach ($order['items'] as $key => $value) {
                $item = $itemModel->getRecordById($key);
                $quantity = $value;
                $items += [$item['name']=>$quantity];
            }

            $this->view->generate('orders/show_view.twig.html', [
                'order' => $order,
                'items' => $items,
            ]);
            return true;
        }

        return false;
    }


    /**
     * Displays form for editing status of existing order
     * 
     * @return boolean
     */
    public function editAction($id)
    {

        AuthController::checkAuth();

        if ($id) {

            $orderModel = new Order();
            $order = $orderModel->getRecordById($id);

            $this->view->generate('orders/edit_view.twig.html', [
                'order' => $order,
            ]);

            return true;
        }

        return false;
    }
    
    /**
     * Updates existing order in DB
     * 
     * @return boolean
     */
    public function updateAction($id)
    {

        AuthController::checkAuth();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $orderModel = new Order();
            $order = $orderModel->getRecordById($id);

            if (isset($_POST['status'])
                && is_numeric($_POST['status'])
                && $_POST['status'] > 0
                && $_POST['status'] <= 4) {


                    $args = array(
                                'id'     => $id,
                                'status' => $_POST['status'],
                                 );

            } else { 

                $this->view->generate('orders/edit_view.twig.html', [
                    'order' => $order,
                    'error' => 'Not all fields are filled ore wrong status',
                ]);

                return true;
            }

            if($orderModel->editRecord($args)) {
                header('location:/orders');
                exit();
            } else {

                $this->view->generate('orders/edit_view.twig.html', [
                    'order' => $order,
                    'error' => 'The rocord has not been changed',
                ]);

                return true;
            }
        } else {
             Router::errorPage404();
        }
    }

    /**
     * Desrtroys existing order in DB
     * 
     * @return boolean
     */
    public function destroyAction($id)
    {

        AuthController::checkAuth();

        if ($id) {
            $orderModel = new Order();
            $orderModel->deleteRecord($id);
            header('location:/orders');
            exit();
        }
    }
}