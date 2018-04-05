
/*
Inspired by https://dribbble.com/shots/2004657-Alarm-Clock-concept
 */

var describeArc, polarToCartesian, setCaptions, future, tps_secondes;

//Global variable in which we will store current date + time of countdown
var future = new Date();





function stopTimer(){
    //On change la date butoire
    future = new Date();
    //On interdit de continuer à répondre au sondage en cours
    pausePoll();
    //On indique qu'il n'y a pas de vote en cours pour ne pas appeler
    //inutilement la fonction à l'avenir
    pollPending = 0;
    console.log("Fin du vote");
    setTitle();
}

function startTimer() {
    //Remise à zéro de la question (on efface les résultats)
//     $("#plop").load('http://alexisfles.ch/vote/question.php');
    //On réouvre le sondage
    resumePoll();
    //Démarrage du timer
    console.log('Starting timer');
    var x = document.forms["timerForm"]["time"].value;
    tps_secondes = parseInt(x);
    var foo = Math.floor(tps_secondes/60);
    var bar = tps_secondes - 60*foo;
    future = new Date();
    future.setMinutes(future.getMinutes()+foo);
    future.setSeconds(future.getSeconds()+bar);
    future.setMilliseconds(0);
    //On indique qu'il y a un vote en cours
    pollPending = 1;
    setTitle();
};

function autoCaptions(){
    setInterval(function() {
        return setCaptions();
    }, 50);
};


polarToCartesian = function(centerX, centerY, radius, angleInDegrees) {
    var angleInRadians;
    angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
    return {
      x: centerX + radius * Math.cos(angleInRadians),
      y: centerY + radius * Math.sin(angleInRadians)
    };
  };

  
describeArc = function(x, y, radius, startAngle, endAngle) {
    var arcSweep, end, start;
    start = polarToCartesian(x, y, radius, endAngle);
    end = polarToCartesian(x, y, radius, startAngle);
    arcSweep = endAngle - startAngle <= 180 ? '0' : '1';
    return ['M', start.x, start.y, 'A', radius, radius, 0, arcSweep, 0, end.x, end.y].join(' ');
  };


setCaptions = function() {
    var dot, hour, hourArc, minArc, minute, pos;
    now = new Date();
    diff_h = (future.getHours()-now.getHours());
    diff_m = (future.getMinutes()-now.getMinutes());
    diff_s = future.getSeconds()-now.getSeconds();
    diff_ms = future.getMilliseconds()-now.getMilliseconds();
    tot_seconde = diff_s+60*diff_m+3600*diff_h;
//     reste_en_s = 60*diff_m+diff_s;
    if (tot_seconde>0){ //Il reste du temps
        seconde = tot_seconde%60;
        mseconde = ((diff_ms+(tot_seconde)*1000)%(1000*60)) % 1000;
//         mseconde = diff_ms;
        hourArc = tot_seconde/tps_secondes*359.999;
        minArc = mseconde /1000 * 360;
        $('.clockArc.hour').attr('d', describeArc(500, 240, 150, 0, hourArc));
        $('.clockDot.hour').attr('d', describeArc(500, 240, 150, hourArc - 3, hourArc));
        $('.clockDot.minute').attr('d', describeArc(500, 240, 170, minArc - 1, minArc));
        if (seconde % 2){
            $('.clockArc.minute').attr('d', describeArc(500, 240, 170, minArc, 360));
        }
        else{
            $('.clockArc.minute').attr('d', describeArc(500, 240, 170, 0, minArc));
        }
        dot = $(".clockDot.hour");
        pos = polarToCartesian(500, 240, 150, hourArc);
        dot.attr("cx", pos.x);
        dot.attr("cy", pos.y);
        dot = $(".clockDot.minute");
        pos = polarToCartesian(500, 240, 170, minArc);
        dot.attr("cx", pos.x);
        dot.attr("cy", pos.y);
        min = Math.floor(tot_seconde/60);
        if (seconde<10){
            s = '0' + seconde;
        }
        else{
            s = seconde;
        }
        return $('#time').text(min + ':' + s);
    }
    else{ //Fin du compte à rebours
        if (pollPending==1){
            stopTimer();
        }
        //Mise à jour du point pour les minutes
        dot = $(".clockDot.hour");
        pos = polarToCartesian(500, 240, 150, 0);
        dot.attr("cx", pos.x);
        dot.attr("cy", pos.y);
        //Mise à jour du point pour les secondes
        dot = $(".clockDot.minute");
        dot.attr("cx", pos.x);
        dot.attr("cy", pos.y);
        //Clignotage
        seconde = tot_seconde%60;
        if (seconde % 2){
            $('.clockArc.minute').attr('d', describeArc(500, 240, 170, 0, 359.999));
            $('.clockArc.hour').attr('d', describeArc(500, 240, 150, 0, 0.00001));
        }
        else{
            $('.clockArc.minute').attr('d', describeArc(500, 240, 170, 0, 0.0001));
            $('.clockArc.hour').attr('d', describeArc(500, 240, 150, 0, 359.999));
        }
        return $('#time').text("0:00");
    }
  };


setTitle = function(){
    $('#day').text("Vote");
    if (pollPending==1){
        $('#date').text("Open");
    }
    else{
        $('#date').text("Closed");
    }
    TweenMax.staggerFrom(".clockArc", .5, {
      drawSVG: 0,
      ease: Power3.easeOut
    }, 0.3);
    TweenMax.from("#time", 1.0, {
      attr: {
        y: 350
      },
      opacity: 0,
      ease: Power3.easeOut,
      delay: 0.5
    });
    TweenMax.from(".dayText", 1.0, {
      attr: {
        y: 310
      },
      opacity: 0,
      delay: 1.0,
      ease: Power3.easeOut
    });
    return TweenMax.from(".dateText", 1.0, {
      attr: {
        y: 350
      },
      opacity: 0,
      delay: 1.5,
      ease: Power3.easeOut
    });
    console.log("hop");
  };