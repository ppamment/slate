<?php

class DefaultController extends Website_Controller_Action
{

    public function preDispatch()
    {
        $this->enableLayout();
        $this->view->headTitle()->setSeparator(" - ");
        $this->view->headTitle("Slate Projects");
    }

    public function homeAction()
    {

    }

	public function wysiwygAction ()
    {
        $this->view->headTitle($this->view->document->getTitle());
	}

    public function artistAction ()
    {
        $artistKey = $this->_getParam("artist");
        $artists = new Object_Artist_List();
        $artists->setCondition("o_key = '" . $artistKey . "'");

        $this->view->artist = current($artists->getObjects());

        $this->view->headTitle("Artist");
        $this->view->headTitle($this->view->artist->getName());
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
    }

    public function aboutAction()
    {
        $newsletter = $this->getRequest()->getParam("newsletter");
        if($newsletter){
            mail("alex@slateprojects.com", "Newsletter subscription", "<p>First Name: {$newsletter['first_name']}</p><p>Last Name: {$newsletter['last_name']}</p><p>Email: {$newsletter['email']}</p>");
            $this->view->subscribed = true;
        }
        $this->view->headTitle("About");
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
        } elseif(count($current) > 0){
            $ex = current($current);
            $this->view->headTitle($ex->getName());
        }
    }

    public function newsAction()
    {
        $news = new Object_NewsItem_List();
        $news->setOrderKey("date");
        $news->setOrder("DESC");

        $news = $news->getObjects();

        $this->view->news = $news;

        $this->view->headTitle("News");
    }

    public function newsitemAction()
    {
        $newsKey = $this->_getParam("news");
        $news = new Object_NewsItem_List();
        $news->setCondition("o_key = '" . $newsKey . "'");

        $this->view->news = current($news->getObjects());

        $this->view->headTitle("News");
        $this->view->headTitle($this->view->news->getTitle());
    }
}
