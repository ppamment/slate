<?php

class DefaultController extends Website_Controller_Action
{

    public function preDispatch()
    {
        $this->enableLayout();
        $this->view->headTitle()->setSeparator(" - ");
        $conf = Pimcore_Config::getSystemConfig();
        if($conf->general->site == "averard"){
            $this->view->headTitle("The Averard Hotel");
            $this->view->site = "averard";
        } else {
            $this->view->headTitle("Slate Projects");
            $this->view->site = "slate";
        }
    }

    public function homeAction()
    {
        $this->view->headMeta()->appendName("description", $this->view->document->getDescription());
    }

	public function wysiwygAction ()
    {
        $this->view->headTitle($this->view->document->getTitle());
        $this->view->headMeta()->appendName("description", $this->view->document->getDescription());
	}

    public function artistAction ()
    {
        $artistKey = $this->_getParam("artist");
        $artists = new Object_Artist_List();
        $artists->setCondition("o_key = '" . $artistKey . "'");

        $this->view->artist = current($artists->getObjects());

        $this->view->headTitle("Artist");
        $this->view->headTitle($this->view->artist->getName());
        $this->view->headMeta()->appendName("description", $this->view->artist->getBio());
    }

    public function artistsAction()
    {
        $artists = new Object_Artist_List();
        $artists->setOrder("name");


        $artists = $artists->getObjects();

        usort($artists, function($a, $b){
            $aLast = end(explode(' ', $a->getName()));
            $bLast = end(explode(' ', $b->getName()));

            return strcasecmp($aLast, $bLast);
        });

        $this->view->artists = $artists;

        $this->view->headTitle("Artists");
        $this->view->headMeta()->appendName("description", $this->view->document->getDescription());
    }

    public function aboutAction()
    {
        $newsletter = $this->getRequest()->getParam("newsletter");
        if($newsletter){
            mail("alex@slateprojects.com", "Newsletter subscription", "<p>First Name: {$newsletter['first_name']}</p><p>Last Name: {$newsletter['last_name']}</p><p>Email: {$newsletter['email']}</p>");
            $this->view->subscribed = true;
        }
        $this->view->headTitle("About");
        $this->view->headMeta()->appendName("description", $this->view->document->getDescription());
    }

    public function exhibitionsAction()
    {
        $viewKey = $this->getRequest()->getParam("view");

        $exhibitions = new Object_Exhibition_List();
        $exhibitions->setOrderKey("start");
        $exhibitions->setOrder("DESC");

        $exhibitions = $exhibitions->getObjects();

        $upcoming = $current = $past = $view = array();

        foreach($exhibitions as $exhibition){
            /** @var Object_Exhibition $exhibition */
            if($exhibition->getStart()->getTimestamp() > time()){
                $upcoming[] = $exhibition;
            } elseif($exhibition->getStart()->getTimestamp() < time() and $exhibition->getEnd()->getTimestamp() > time()){
                $current[] = $exhibition;
            } else {
                $past[] = $exhibition;
            }
            if($exhibition->getKey() == $viewKey){
                $view[] = $exhibition;
            }
        }

        if(count($current) == 0 and count($upcoming) > 0){
            $current[] = end($upcoming);
        }
        if(current($current) == current($view)){
            $view = array();
        }

        if(count($view) > 0){
            $active = "view";
        } elseif(count($current) > 0){
            $active = "current";
        } elseif(count($upcoming) > 0){
            $active = "upcoming";
        } else {
            $active = "past";
        }

        $this->view->upcoming = $upcoming;
        $this->view->current = $current;
        $this->view->past = $past;
        $this->view->view = $view;
        $this->view->active = $active;

        $this->view->headTitle("Exhibitions");
        if(count($view) > 0)
        {
            $ex = current($view);
            $this->view->headTitle($ex->getName());
            $this->view->headMeta()->appendName("description", $ex->getDescription());
        } elseif(count($current) > 0){
            $ex = current($current);
            $this->view->headTitle($ex->getName());
            $this->view->headMeta()->appendName("description", $ex->getDescription());
        }

    }

    public function newsAction()
    {
        $news = new Object_NewsItem_List();
        $news->setOrderKey("date");
        $news->setOrder("DESC");

        $allNews = $news->getObjects();
        $chunked = array(0 => array(), 1 => array());
        foreach($allNews as $k => $item){
            $chunked[$k%2][] = $item;
        }
        $this->view->news = $chunked;

        $this->view->headTitle("News");
        $this->view->headMeta()->appendName("description", $this->view->document->getDescription());
    }

    public function newsitemAction()
    {
        $newsKey = $this->_getParam("news");
        $news = new Object_NewsItem_List();
        $news->setCondition("o_key = '" . $newsKey . "'");

        $this->view->news = current($news->getObjects());

        $this->view->headTitle("News");
        $this->view->headTitle($this->view->news->getTitle());
        $this->view->headMeta()->appendName("description", $this->view->news->getTitle() . " - " . $this->view->news->getSubtitle());
    }
}
