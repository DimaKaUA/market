<?php

class ErrorController extends Controller{

    function indexAction()
    {
        $this->view->generate('404_view.twig.html');
        return true;
    }
}
