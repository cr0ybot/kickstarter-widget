kickstarter-widget
==================

Simple PHP web scraper for displaying what Kickstarter projects you are backing. Place it on your blog or website with an iframe.

This widget utilizes the PHP Simple DOM Parser at http://simplehtmldom.sourceforge.net/

Kickstarter-widget will scrape Kickstarter's profile page for the requested amount of projects you are backing.

The url takes 2 parameters:
u: username or string of numbers in the url at http://www.kickstarter.com/profiles/###username###/projects/backed
n: (optional) number of projects to show in the list, 4 is default
    
ex: http://www.yourwebsite.com/kickstarter.php?u=username&n=6

If using an iframe to display widget, the height for n=1 should be 110.
For the default n=4, height should be 290.
For each n past 1, add 60 (for n=2 height=170, for n=3 height=230, etc).

    height = n*60 + 50

Copyright (c) 2012 Cory Hughart, cr0ybot.com, under the MIT license

This file is subject to the terms and conditions defined in file 'LICENSE.txt', which is part of this source code package.
