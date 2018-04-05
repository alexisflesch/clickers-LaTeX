<?php
    session_start();
?>


<!DOCTYPE html>
<html >
<head>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js'></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
    <script type="text/x-mathjax-config">
        MathJax.Hub.Config({tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']]}});
    </script>
    
    <script src="./js/clock.js"></script>
    <script src="./js/reload.js"></script>

    <script type="text/javascript">
        $(window).load(init);
        window.setInterval(conditionalUpdate, 2000);
    </script>
    
    <meta charset="UTF-8">
    
    <title>Timer</title>
  
  
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>


<section class="container">


<div class="one">
    <ul id="plop">
    </ul>
</div>

<div class="two">
  <svg viewBox="0 0 800 480">
	<defs>
		<pattern id="dotPattern"
						 x="0" y="0" width="10" height="10"
						 patternUnits="userSpaceOnUse">
				<circle class="bgDot" cx="5" cy="5" r="2" />
		</pattern>
		
		<radialGradient id="backHoleBelowClock" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
			<stop offset="50%"  style="stop-color:rgb(0,0,0);stop-opacity:0.7"/>
			<stop offset="100%"	style="stop-color:rgb(0,0,0);stop-opacity:0"/>
		</radialGradient>

		<radialGradient id="blackVignette" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
      <stop offset="40%" style="stop-color:rgb(0,0,0);stop-opacity:0" />
      <stop offset="100%" style="stop-color:rgb(0,0,0);stop-opacity:1" />
    </radialGradient>

		<filter id="glow">
				<feGaussianBlur stdDeviation="2.5" result="coloredBlur"/>
				<feMerge>
						<feMergeNode in="coloredBlur"/>
						<feMergeNode in="SourceGraphic"/>
				</feMerge>
		</filter>		
		
		<filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
			<feGaussianBlur in="SourceAlpha" stdDeviation="3" result="shadow"/>
			<feOffset dx="1" dy="1"/>
			<feMerge>
				<feMergeNode/>
				<feMergeNode in="SourceGraphic"/>
			</feMerge>
		</filter>		
		
	</defs>
	
	<!-- Background objects -->
	<rect x="0" y="0" width="100%" height="100%" style="stroke: #000000; fill: url(#dotPattern);" /> 	
	<ellipse cx="500" cy="240" rx="300" ry="300" fill="url(#backHoleBelowClock)"/>	
	<ellipse cx="500" cy="240" rx="600" ry="600" fill="url(#blackVignette)"/>	
	
	<!-- Clock objects -->
	<circle class="clockCircle hour" cx="500" cy="240" r="150" stroke-width="6" />
	<path id="arcHour" class="clockArc hour" stroke-width="6" stroke-linecap="round" filter="url(#glow)" />
	<circle class="clockDot hour" r="8" filter="url(#glow)" />

	<circle class="clockCircle minute" cx="500" cy="240" r="170" stroke-width="3" />
	<path id="arcMinute" class="clockArc minute" stroke-width="3" stroke-linecap="round" filter="url(#glow)" />
	<circle class="clockDot minute" r="5" filter="url(#glow)" />
	
	<!-- Caption objects -->
	<text id="time" class="caption timeText" x="500" y="240" stroke-width="0" text-anchor="middle" alignment-baseline="middle"  filter="url(#shadow)"></text>
	
	<text id="day" class="caption dayText" x="300" y="210" stroke-width="0" text-anchor="end" alignment-baseline="middle"  filter="url(#shadow)"></text>
	<text id="date" class="caption dateText" x="300" y="250" stroke-width="0" text-anchor="end" alignment-baseline="middle" filter="url(#shadow)"></text>
	
	
        <text id="voteid" class="caption idText" x="10" y="30" stroke-width="0" text-anchor="left" alignment-baseline="middle" filter="url(#shadow)">Poll id : </text>

	
    </svg>
    <div class="three">
      <span style="left">
        <form name="timerForm" action="javascript:startTimer();">
            <input type="text" name="time" value="120">
            <input type="submit" value="Start">
        </form>
        
        <form class="bla" action="javascript:stopTimer();">
            <input type="submit" value="Stop">
        </form>
        

       </span>
       
       <span style="float:right">
        <form action="javascript:toggle();">
            <input class="untoggled" type="submit" id="toggleButton" value="Show results">
        </form>
        
        <form class="bla" action="javascript:resetFromTimer();">
            <input class="untoggled" type="submit" value="Reset poll" id="resetButton">
        </form>
        </span>
        
    </div>
    
</div>


</section>



</body>
</html>
