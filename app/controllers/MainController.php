<?php 

include_once ROOT . '/models/Item.php';
include_once ROOT . '/models/Category.php';

class MainController extends Controller {
    
    /**
     * Displays main page of site with all categories
     *
     * @return boolean
     */
    public function indexAction()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getList();
        
        $this->view->generate('main/index_view.twig.html', $categories);

        return true;
    }

    /**
     * Displays category with the relevant items
     *
     * @return boolean
     */
    public function catalogAction($categoryName)
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getList();

        $requestedCategory = $categoryModel->getRecordByName($categoryName);

        $itemModel = new Item();
        $itemsOfCategory = $itemModel->getRecordByCategoryId($requestedCategory['id']);
        
        $this->view->generate('main/catalog_view.twig.html', [
                'itemsOfCategory'     => $itemsOfCategory,
                'categories'          => $categories,
                'requestedCategoryId' => $requestedCategory['id'],
        ]);

        return true;
    }
}