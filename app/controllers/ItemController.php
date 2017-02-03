<?php 

include_once ROOT . '/models/Item.php';
include_once ROOT . '/models/Category.php';
include_once ROOT . '/controllers/AuthController.php';

/**
 * Class ItemController
 */
class ItemController extends Controller {
    
    /**
     * Displays a listing of the items
     *
     * @return boolean
     */
    public function indexAction()
    {

        AuthController::checkAuth();

        $itemModel = new Item();
        $allItems = $itemModel->getListWithCategoryName();

        $this->view->generate('items/index_view.twig.html', $allItems);
        return true;
    }

    /**
     * Displays requested item
     * 
     * @return boolean
     */
    public function showAction($id)
    {

        AuthController::checkAuth();

        if ($id) {
            $itemModel = new Item();
            $item = $itemModel->getRecordById($id);

            $categoryModel = new Category();
            $category = $categoryModel->getRecordById($item['category_id']);

            $this->view->generate('items/show_view.twig.html', [
                'item'     => $item,
                'category' => $category,
            ]);
            return true;
        }

        return false;
    }

    /**
     * Displays form for creation new item
     * 
     * @return boolean
     */
    public function newAction()
    {

        AuthController::checkAuth();

        $categoryModel = new Category();
        $categories = $categoryModel->getList();
        $this->view->generate('items/new_view.twig.html', [
            'categories' => $categories,
        ]);
        return true;
    }

    /**
     * Displays form for editing new item
     * 
     * @return boolean
     */
    public function editAction($id)
    {

        AuthController::checkAuth();

        if ($id) {

            $itemModel = new Item();
            $item = $itemModel->getRecordById($id);

            $categoryModel = new Category();
            $categories = $categoryModel->getList();

            $this->view->generate('items/edit_view.twig.html', [
                'item'       => $item,
                'categories' => $categories,
        ]);

            return true;
        }

        return false;
    }

    /**
     * Stores new item in DB or return back 
     * to previous form with errors
     * 
     * @return boolean
     */
    public function createAction()
    {

        AuthController::checkAuth();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $categoryModel = new Category();
            $categories = $categoryModel->getList();

            if (isset($_POST['name'], $_POST['category_name'], $_POST['description'], $_POST['price']) 
                && strlen($_POST['name']) > 0
                && strlen($_POST['category_name']) > 0
                && strlen($_POST['description']) > 0
                && is_numeric($_POST['price'])) {

                    $category = $categoryModel->getRecordByName($_POST['category_name']);

                    $args = array(
                                'name'         => $_POST['name'],
                                'category_id'  => $category['id'],
                                'description'  => $_POST['description'],
                                'price'        => $_POST['price'],
                                 );

            } else { 
                $data = array(
                            'name'         => $_POST['name'],
                            'description'  => $_POST['description'],
                            'price'        => $_POST['price'],
                            'error'        => 'Not all fields are filled',
                             );
                $this->view->generate('items/new_view.twig.html', [
                    'item'       => $data,
                    'categories' => $categories,
                ]);
                return true;
            }

            $itemModel = new Item();

            if($itemModel->addRecord($args)) {
                header('location:/items');
                exit();
            } else {
                $data = array(
                            'name'         => $args['name'],
                            'category_id'  => $args['category_id'],
                            'description'  => $args['description'],
                            'price'        => $args['price'],  
                            'error'        => 'Error recording to DB'
                             );
                $this->view->generate('items/new_view.twig.html', [
                    'item'       => $data,
                    'categories' => $categories,
                ]);
                return true;
            }
        } else {
             Router::errorPage404();
        }
    }
    
    /**
     * Updates existing item in DB
     * 
     * @return boolean
     */
    public function updateAction($id)
    {

        AuthController::checkAuth();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $categoryModel = new Category();
            $categories = $categoryModel->getList();

            if (isset($_POST['name'], $_POST['category_name'], $_POST['description'], $_POST['price']) 
                && strlen($_POST['name']) > 0
                && strlen($_POST['category_name']) > 0
                && strlen($_POST['description']) > 0
                && is_numeric($_POST['price'])) {

                $category = $categoryModel->getRecordByName($_POST['category_name']);

                    $args = array(
                                'id'           => $id,
                                'name'         => $_POST['name'],
                                'category_id'  => $category['id'],
                                'description'  => $_POST['description'],
                                'price'        => $_POST['price'],
                                 );

            } else { 

                $data = array(
                            'id'           => $id,
                            'name'         => $_POST['name'],
                            'description'  => $_POST['description'],
                            'price'        => $_POST['price'],
                            'error'        => 'Not all fields are filled',
                             );

                $this->view->generate('items/edit_view.twig.html', [
                    'item'       => $data,
                    'categories' => $categories,
                ]);
                return true;
            }

            $itemModel = new Item();

            if($itemModel->editRecord($args)) {
                header('location:/items');
                exit();
            } else {
                $data = array(
                            'id'           => $id,
                            'name'         => $args['name'],
                            'category_id'  => $args['category_id'],
                            'description'  => $args['description'],
                            'price'        => $args['price'], 
                            'error'        => 'The rocord has not been changed',
                             );
                $this->view->generate('items/edit_view.twig.html', [
                    'item'       => $data,
                    'categories' => $categories,
                ]);
                return true;
            }
        } else {
             Router::errorPage404();
        }
    }

    /**
     * Desrtroys existing item in DB
     * 
     * @return boolean
     */
    public function destroyAction($id)
    {

        AuthController::checkAuth();

        if ($id) {
            $itemModel = new Item();
            $itemModel->deleteRecord($id);
            header('location:/items');
            exit();
        }
    }
}