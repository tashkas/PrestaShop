<?php

/**
  * Statistics
  * @category stats
  *
  * @author Damien Metzger / Epitech
  * @copyright Epitech / PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.1
  */
  
class GraphArtichow extends ModuleGraphEngine
{
	private	$_graph;
	private	$_plot = array();	//for simple-layer graphics
	private	$_group = NULL; //for multi-layer graphics
	private	$_error = 0;

	function __construct($type = null)
	{
		if ($type != null)
		{
			require_once(dirname(__FILE__).'/artichow/Graph.class.php');
			$this->_graph = new Graph();
			$this->_graph->setAntiAliasing(true);
			parent::__construct($type);
		}
		else
		{
	        $this->name = 'graphartichow';
	        $this->tab = 'Stats Engines';
	        $this->version = 1.0;
			$this->page = basename(__FILE__, '.php');
	        Module::__construct();
	        $this->displayName = $this->l('Artichow');
	        $this->description = $this->l('Artichow is a library which enable the display of simple picture-based graphs using PHP and GD.');
		}
	}

	function install()
	{
		return (parent::install() AND $this->registerHook('GraphEngine'));
	}
    
	public static function hookGraphEngine($params, $drawer)
	{
		return '<img src="'.$drawer.'&width='.$params['width'].'&height='.$params['height'].'" />';
	}
		
	private function drawLine($values)
	{
		require_once(dirname(__FILE__).'/artichow/LinePlot.class.php');
		if (!is_array($values[0])) //simpleline
		{
			$this->_plot = new LinePlot(array_values($values));
			$this->_plot->setFillGradient(new LinearGradient(new Color(255, 246, 211, 20), new Color(240, 225, 160, 50), 90));
			$this->_plot->setColor(new Color(80, 50, 0, 0));
		}
		else //multilines
		{
			$tabColors = array(new Color(120, 120, 30, 10), new Color(66, 127, 195, 10), new Color(195, 65, 60, 10), new Color(85, 163, 57, 10), new Color(255, 35, 152, 10));
			$tabFilledColors = array(new Color(120, 120, 30, 90), new Color(66, 127, 195, 90), new Color(195, 65, 60, 90), new Color(85, 163, 57, 90), new Color(255, 35, 152, 90));

			$this->_group = new PlotGroup;
			$i = 0;
			foreach ($values as $val)
			{
				$this->_plot[$i] = new LinePlot($val);
				$this->_plot[$i]->setColor($tabColors[$i % 5]);
				$this->_plot[$i]->setFillColor($tabFilledColors[$i % 5]);
				$this->_group->add($this->_plot[$i]);
				$i++;
			}
		}
	}

	private function drawColumn($values)
	{
		require_once(dirname(__FILE__).'/artichow/BarPlot.class.php');

		if (!is_array($values[0])) //simpleline
		{
			$this->_plot = new BarPlot(array_values($values));
			$this->_plot->label->set(array_values($values));
			$this->_plot->label->setColor(new Color(110, 110, 110));
			$this->_plot->label->move(0, -7);
			$this->_plot->label->setAlign(Positionable::CENTER, NULL);
			$this->_plot->setBarGradient(new LinearGradient(new Color(254, 255, 216), new Color(233, 231, 218), 90));
			$this->_plot->barBorder->setColor(new Color(115, 115, 150));
			$this->_plot->barShadow->setSize(1);
			$this->_plot->barShadow->setColor(new Color(215, 215, 215));
			$this->_plot->barShadow->setPosition(Shadow::RIGHT_TOP);
			$this->_plot->barShadow->smooth(TRUE);
		}
		else
		{
			$tabColors = array(new Color(120, 120, 30, 10), new Color(66, 127, 195, 10), new Color(195, 65, 60, 10), new Color(85, 163, 57, 10), new Color(255, 35, 152, 10));
			$tabFilledColors = array(new Color(120, 120, 30, 90), new Color(66, 127, 195, 90), new Color(195, 65, 60, 90), new Color(85, 163, 57, 90), new Color(255, 35, 152, 90));

			$this->_group = new PlotGroup;
			$i = 0;
			foreach ($values as $val)
			{
				$this->_plot[$i] = new BarPlot(array_values($val));
				$this->_plot[$i]->label->set(array_values($val));
				$this->_plot[$i]->label->setColor($tabColors[$i % 5]);
				$this->_plot[$i]->label->move(0, -7);
				$this->_plot[$i]->label->setAlign(Positionable::CENTER, NULL);
				$this->_plot[$i]->setBarColor($tabFilledColors[$i % 5]);
				$this->_plot[$i]->barBorder->setColor($tabColors[$i % 5]);
				$this->_plot[$i]->barShadow->setSize(1);
				$this->_plot[$i]->barShadow->setColor(new Color(215, 215, 215));
				$this->_plot[$i]->barShadow->setPosition(Shadow::RIGHT_TOP);
				$this->_plot[$i]->barShadow->smooth(TRUE);
				$this->_group->add($this->_plot[$i]);
				$i++;
			}
		}
	}
	
	private function setErrorImage()
	{
		require_once(dirname(__FILE__).'/artichow/BarPlot.class.php');

		$this->_plot = new BarPlot(array(0));
		$this->_type = 'column';
		$this->_plot->setSize(0, 0);
		$this->_plot->setCenter(-20, -20);
	}

	private function drawPie($values)
	{
		require_once(dirname(__FILE__).'/artichow/Pie.class.php');
		$exploder = array();
		$counter = sizeof($values);
		
		$this->_plot = new Pie(array_values($values), Pie::COLORED);
		$this->_plot->set3D(10);
		$this->_plot->setLabelPrecision(2);
		$this->_plot->setLabelPosition(2);
		$this->_plot->legend->shadow->setSize(1);
		$this->_plot->legend->setModel(Legend::MODEL_BOTTOM);
		$this->_plot->legend->setPosition(NULL, 0.87);
		$this->_plot->setSize(0.90, 0.90);
		for ($i = 0; $i < $counter; $i++)
			$exploder[$i] = 8;
		$this->_plot->explode($exploder);
	}

	private function drawLineColumn($y_max)
	{
		if ($this->_group == NULL)
		{
			$this->_plot->setYMax($y_max);
			$this->_plot->setSize(1, 1);
			$this->_plot->setCenter(0.508, 0.53);
			$this->_plot->setPadding(28, 10, NULL, 40);
		}
		else
		{
			foreach ($this->_plot as $plot)
				$plot->setYMax($y_max);
			$this->_group->setYMax($y_max);
			$this->_group->setSize(1, 1);
			$this->_group->setCenter(0.508, 0.53);
			$this->_group->setPadding(28, 10, NULL, 40);
		}
	}

	public function createValues($values)
	{
		if (sizeof($values) == 0)
		{
			$this->setErrorImage();
			return;
		}
		switch ($this->_type)
		{
			case 'pie':
				$this->drawPie($values);
				break;
			case 'line':
				$this->drawLine($values);
				$this->drawLineColumn($this->getYMax($values));
				break;
			case 'column':
			default:
				$this->drawColumn($values);
				$this->drawLineColumn($this->getYMax($values));
				break;
		}
	}
	
	public function setSize($width, $height)
	{
		if (Validate::isUnsignedInt($width) AND Validate::isUnsignedInt($height))
			$this->_graph->setSize($width, $height);
	}
	
	public function setLegend($legend)
	{
		if ($this->_plot == NULL && $this->_group == NULL)
			return;
		foreach ($legend as $k => $val)
			$legend[$k] = html_entity_decode(htmlentities(html_entity_decode($val), ENT_NOQUOTES, 'utf-8'));
		switch ($this->_type)
		{
			case 'pie':
				$this->_plot->setLegend($legend);
				break;
			case 'column':
			default:
				if ($this->_group == NULL)
				{
					if (isset($this->_plot->xAxis, $this->_plot->yAxis, $this->_plot->grid))
					{
						$this->_plot->xAxis->setLabelText($legend);
						$this->_plot->xAxis->label->setFont(new Tuffy(7));
						$this->_plot->yAxis->setLabelPrecision(0);
						$this->_plot->grid->setType(Line::DASHED);
					}
				}
				else
				{
					if (isset($this->_group->axis->bottom, $this->_group->axis->left, $this->_group->grid))
					{
						$this->_group->axis->bottom->setLabelText($legend);
						$this->_group->axis->bottom->label->setFont(new Tuffy(7));
						$this->_group->axis->left->setLabelPrecision(0);
						$this->_group->grid->setType(Line::DASHED);
					}
				}
				break;
		}
	}

	public function setTitles($titles)
	{
		if ($this->_plot == NULL && $this->_group == NULL)
			return;
		if (isset($titles['main']))
		{
			if (is_array($titles['main']) && $this->_group != NULL)
			{
				$i = 0;
				foreach ($titles['main'] as $key => $val)
					if ($key != 0)
						$this->_group->legend->add($this->_plot[$i++], $val, ($this->_type == 'line') ? Legend::LINE : Legend::BACKGROUND);
				$titles['main'] = $titles['main'][0];
			}
			$this->_graph->title->set($titles['main']);
			$this->_graph->title->setFont(new Tuffy(10));
			$this->_graph->title->setAlign(Positionable::CENTER, Positionable::MIDDLE);
		}
		if (isset($titles['x']))
		{
			if ($this->_plot != NULL && !is_array($this->_plot))
			{
				$this->_plot->xAxis->title->set($titles['x']);
				$this->_plot->xAxis->title->setFont(new Tuffy(10));
				$this->_plot->xAxis->setTitleAlignment(Label::RIGHT);
			}
			else
			{
				$this->_group->axis->bottom->title->set($titles['x']);
				$this->_group->axis->bottom->title->setFont(new Tuffy(10));
				$this->_group->axis->bottom->setTitleAlignment(Label::RIGHT);
			}
		}
		if (isset($titles['y']))
		{
			if ($this->_plot != NULL && !is_array($this->_plot))
			{
				$this->_plot->yAxis->title->set($titles['y']);
				$this->_plot->yAxis->title->setFont(new Tuffy(10));
				$this->_plot->yAxis->setTitleAlignment(Label::TOP);
			}
			else
			{
				$this->_group->axis->left->title->set($titles['y']);
				$this->_group->axis->left->title->setFont(new Tuffy(10));
				$this->_group->axis->left->setTitleAlignment(Label::TOP);
			}
		}
	}
	
	public function draw()
	{
		if ($this->_plot != NULL && !is_array($this->_plot))
			$this->_graph->add($this->_plot);
		if ($this->_group != NULL)
			$this->_graph->add($this->_group);
		$this->_graph->draw();
	}
	
	private function getYMax($values)
	{
		$max = 0;
		if (isset($values[0]) && is_array($values[0]))
		{
			foreach ($values as $val)
				foreach ($val as $k => $nb)
					if ($nb > $max)
						$max = $nb;
		}
		else
		{
			foreach ($values as $k => $val)
				if ($val > $max)
					$max = $val;
		}
		return ($max < 4) ? 4 : (round($max, 0));
	}
}

?>
