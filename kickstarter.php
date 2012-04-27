<?php

/**
 * This will scrape Kickstarter's profile page for the requested
 * amount of projects you are backing.
 * 
 * The url takes 2 parameters:
 * u: username or string of numbers in the url at
 * http://www.kickstarter.com/profiles/###username###/projects/backed
 * n: (optional) number of projects to show in the list, 4 is default
 * 
 * ex: http://www.yourwebsite.com/kickstarter.php?u=username&n=6
 *
 * If using an iframe to display widget, the height for n=1 should be 110.
 * For the default n=4, height should be 290.
 * For each n past 1, add 60 (for n=2 height=170, for n=3 height=230, etc).
 * height = n*60 + 50
 *
 * Copyright (c) 2012 Cory Hughart, cr0ybot.com, under the MIT license
 * 
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is part of this source code package.
 */
    
require 'simple_html_dom.php';

$card = 'error loading stats from kickstarter';

// if u is set in url, fetch kickstarter data
if (isset($_GET['u']))
{
	$user = $_GET['u'];
	
	// use curl to grab html
	$ch = curl_init('http://www.kickstarter.com/profiles/'.$user.'/projects/backed');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$htmlStr = curl_exec($ch);
	curl_close($ch);
	
	// replace absolute path links
	$htmlStr = str_replace('href="/','target="_blank" href="http://www.kickstarter.com/',$htmlStr);
	
	// initialize simple html dom (http://simplehtmldom.sourceforge.net/)
	$html = new simple_html_dom();
	
	// load html from curl string
	$html->load($htmlStr);
	$data = $html->find('div.project-card');
	
	// this will hold the html strings for each project that is displayed
	$projects = array();
	
	// number of projects to list, configurable through url paramater
	$num = 4;
	if (isset($_GET['n'])) $num = $_GET['n'];
	
	// grab elements of each project and create new html string
	for ($i = 0; $i < $num; $i++)
	{
		if ($i < count($data))
		{
			$projStr = '';
			
			// grab link to project
			$href = $data[$i]->find('a',0)->href;
			
			$projStr .= '<li class="project">';
			
			// grab & reformat image
			$img = $data[$i]->find('img[alt=Photo-full]',0);
			$img->width = '56';
			$img->height = '42';
			$projStr .= $img;
			
			$projStr .= '<span class="title">'.$data[$i]->find('a',1)->plaintext.'</span>';
			$projStr .= '<br />';
			$projStr .= $data[$i]->find('span',0);
			
			$projStr .= '<a id="link" href="'.$href.'" target="_blank"><span></span></a>';
			$projStr .= '</li>';
			array_push($projects, $projStr);
		}
		else break;
	}
	
	// finalize widget html
	$card = '<div id="widget">';
	$card .= '<h3><span id="kick">Kick</span><span id="starter">starter</span> Projects I\'m Backing:</h3>';
	$card .= '<ul class="projlist">';
	$card .= implode($projects);
	$card .= '</ul>';
	$card .= '<div id="seeall"><a href="http://www.kickstarter.com/profiles/'.$user.'/projects/backed" target="_blank">See all '.count($data).'</a></div>';
	$card .= '</div>';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Kickstarter Projects I'm Backing</title>
<style>
	html,body {margin:0;padding:0;}
	body {color:#999;background-color:none;font-size:10px;font-family:Arial,Helvetica,sans-serif;}
	a { color:#55a4f2; text-decoration:none; }
	a:hover { color:rgb(16, 113, 209); }
	h3 {display:block;height:25px;margin:0;padding:0;}
	#widget {background-color:none;padding:5px;}
	.title {color:#55a4f2;}
	ul {list-style:none;padding:0;margin:0;}
	ul>li {position:relative;display:block;height:42px;padding:4px;margin-bottom:6px;background-color:#FFF;border-radius:3px;border:2px solid #EEE;box-shadow:inset 0px 0px 0px 1px #DDD;}
	ul>li>img {float:left;padding-right:8px;}
	#kick {color:#333;text-transform:uppercase;}
	#starter {color:#87C442;text-transform:uppercase;}
	#link {display:block;position:absolute;width:100%;height:100%;top:0;left:0;z-index:1;}
	#link:hover {box-shadow:inset 0px 0px 0px 1px #87C442;}
	#seeall {height:15px;text-align:right;}
</style>
</head>

<body>

<?php echo $card; ?>

</body>
</html>