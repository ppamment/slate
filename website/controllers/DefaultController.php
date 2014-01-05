<?php

class DefaultController extends Website_Controller_Action
{

    public function preDispatch()
    {
        $this->enableLayout();
    }

    public function homeAction()
    {

    }

	public function wysiwygAction ()
    {

	}

    public function artistAction ()
    {
        $artistKey = $this->_getParam("artist");
        $artists = new Object_Artist_List();
        $artists->setCondition("o_key = '" . $artistKey . "'");

        $this->view->artist = current($artists->getObjects());
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
    }

    public function aboutAction()
    {
        $newsletter = $this->getRequest()->getParam("newsletter");
        if($newsletter){
            mail("alex@slateprojects.com", "Newsletter subscription", "<p>First Name: {$newsletter['first_name']}</p><p>Last Name: {$newsletter['last_name']}</p><p>Email: {$newsletter['email']}</p>");
            $this->view->subscribed = true;
        }

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
    }
}
