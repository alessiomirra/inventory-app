<?php 
namespace App\Controllers; 

use PDO; 

abstract class BaseController 
{
    protected string $tplDir = 'app/Views/'; 
    protected $content = ''; 
    protected $layout = 'layout/index.tpl.php';

    public function __construct(
        protected ?PDO $conn = null
    )
    {}

    public function display() :void
    {
        require $this->layout; 
    }

    public function setContent(string $content) :void 
    {
        $this->content = $content; 
    }

    public function getContent() :string 
    {
        return $this->content; 
    }

    public function setTplDir(string $dir) :void 
    {
        $this->tplDir = $dir; 
    }

    public function getTplDir() :string 
    {
        return $this->tplDir;
    }

    public function setLayout(string $layout) :void 
    {
        $this->tplDir = $layout; 
    }

    public function getLayout() :string 
    {
        return $this->layout; 
    }

    protected function redirectIfNotLoggedIn(){
        if (!isUserLoggedIn()){
            redirect('/login');
        }
    }
}