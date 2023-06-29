<?php

namespace classes\controllers;

use classes\Core;

class MainController extends Core
{

    /**
     * @param string $page
     * @param bool $redirect set true if user need to redirect
     */
    public function __construct(string $page, bool $redirect = false)
    {
        if (method_exists($this, $page)) {
            $this->$page();
        } else {
            $this->notFound();
        }
    }

    /**
     * Controller for notFound page
     * @return void
     */
    public function notFound(): void
    {
        $this->render('notFound');
    }

    /**
     * Methode to generate page
     * @param string $view name of view to load
     * @param array $data data  pass to page
     * @return void
     */
    protected function render(string $view, array $data = []): void
    {



        $path = (!file_exists($view . ".php")) ? $view . ".php" : "notFound.php";
        $data['path'] = $path;
        $data = (object)$data;

        //get categories  for menu
        $listCats = Categories::getAll() ;

        // Inclure le template
        require_once "views/template.html.php";

    }

    /**
     * Methode return signin page
     * @return Signin
     */
    private function connexion(): Signin
    {
        return new Signin();
    }

    /**
     * Methode return signup page
     * @return Signup
     */
    private function inscription(): Signup
    {
        return new Signup();
    }

    /**
     * return  page home
     * @return Home
     */
    private function home(): Home
    {
        return new Home();
    }
    /**
     * return  page article
     * @return Articles
     */
    private function article(): Articles
    {
        return new Articles();
    }

    /**
     * return page gestions
     * @return Gestions
     */
    private function gestions(): Gestions
    {
        return new Gestions();
    }

}