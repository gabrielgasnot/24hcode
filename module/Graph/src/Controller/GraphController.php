<?php

namespace Graph\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use JpGraph\JpGraph as jpGraph;

class GraphController extends AbstractActionController
{

    public function indexAction()
    {
        $yData = array(40,21,17,14,23);

        // Load modules
        jpGraph::load();
        jpGraph::module('pie');

        // Some data
        $data = array(40,21,17,14,23);

        // Create the Pie Graph.
        $graph = new \PieGraph(350,250);

        $theme_class="DefaultTheme";

        // Set A title for the plot
        $graph->title->Set("A Simple Pie Plot");
        $graph->SetBox(true);

        // Create
        $p1 = new \PiePlot($data);
        $graph->Add($p1);

        $p1->ShowBorder();
        $p1->SetColor('black');
        $p1->SetSliceColors(array('#1E90FF','#2E8B57','#ADFF2F','#DC143C','#BA55D3'));
        $graph->Stroke();

        return new ViewModel([
            //"graphique" => $graph->Stroke()
        ]);
    }
}