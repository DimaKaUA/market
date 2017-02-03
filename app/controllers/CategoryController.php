<?php 

include_once ROOT . '/models/Category.php';
include_once ROOT . '/controllers/AuthController.php';

class CategoryController extends Controller {
    
    /**
     * Displays a listing of the categories
     *
     * @return boolean
     */
    public function indexAction()
    {

        AuthController::checkAuth();

        $categoryModel = new Category();
        $allCategories = $categoryModel->getList();
        
        $this->view->generate('categories/index_view.twig.html', $allCategories);
        return true;
    }

    /**
     * Displays requested category
     * 
     * @return boolean
     */
    public function showAction($id)
    {

        AuthController::checkAuth();

        if ($id) {
            $categoryModel = new Category();
            $category = $categoryModel->getRecordById($id);
            $this->view->generate('categories/show_view.twig.html', $category);
            return true;
        }

        return false;
    }

    /**
     * Displays form for creation new category
     * 
     * @return boolean
     */
    public function newAction()
    {

        AuthController::checkAuth();

        $this->view->generate('categories/new_view.twig.html');
        return true;
    }

    /**
     * Displays form for editing new category
     * 
     * @return boolean
     */
    public function editAction($id)
    {

        AuthController::checkAuth();

        if ($id) {
            $categoryModel = new Category();
            $category = $categoryModel->getRecordById($id);
            $this->view->generate('categories/edit_view.twig.html', $category);

            return true;
        }

        return false;
    }

    /**
     * Stores new category in DB or return back 
     * to previous form with errors
     * 
     * @return boolean
     */
    public function createAction()
    {

        AuthController::checkAuth();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['name']) 
                && strlen($_POST['name']) > 0) {
                    $args = array(
                                'name'    => $_POST['name'],
                                 );
            } else { 
                $data = array(
                            'name'    => $_POST['name'],
                             );
                $this->view->generate('categories/new_view.twig.html', $data);
                return true;
            }

            $categoryModel = new Category();

            if($categoryModel->addRecord($args)) {
                header('location:/categories');
                exit();
            } else {
                $data = array(
                            'name'    => $args['name'],  
                            'error'   => 'Error recording to DB'
                             );
                $this->view->generate('categories/new_view.twig.html', $data);
                return true;
            }
        } else {
             Router::errorPage404();
        }
    }
    
    /**
     * Updates existing category in DB
     * 
     * @return boolean
     */
    public function updateAction($id)
    {

        AuthController::checkAuth();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_POST['name']) 
                && strlen($_POST['name']) > 0) {
                    $args = array(
                                'id'      => $id,
                                'name'    => $_POST['name'], 
                                 );
            } else { 
                $data = array(
                            'id'      => $id,
                            'name'    => $_POST['name'], 
                            'error'   => 'Not all fields are filled',
                             );

                $this->view->generate('categories/edit_view.twig.html', $data);
                return true;
            }

            $categoryModel = new Category();

            if($categoryModel->editRecord($args)) {
                header('location:/categories');
                exit();
            } else {
                $data = array(
                            'id'      => $id,
                            'name'    => $args['name'], 
                            'error'   => 'The rocord has not been changed',
                             );
                $this->view->generate('categories/edit_view.twig.html', $data);
                return true;
            }
        } else {
             Router::errorPage404();
        }
    }

    /**
     * Desrtroys existing category in DB
     * 
     * @return boolean
     */
    public function destroyAction($id)
    {

        AuthController::checkAuth();

        if ($id) {
            $categoryModel = new Category();
            $categoryModel->deleteRecord($id);
            header('location:/categories');
            exit();
        }
    }
}