<?php 

class View {
    public $templateView = 'layouts/template_view.twig.html'; // specify the general view by default.
    
    /**
     * Displays view with template and content
     * 
     */
    public function generate($contentView, array $data = null) 
    {
        
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/../views');
        $renderer = new Twig_Environment($loader);
        echo $renderer->render($contentView, array(
                                                     'template' => $this->templateView,
                                                     'data'     => $data
                                                    )
                              );
    }
}