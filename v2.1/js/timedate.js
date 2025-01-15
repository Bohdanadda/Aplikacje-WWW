function gettheDate() 
        {
            Todays = new Date();
            TheDate = "" + (Todays.getMonth() + 1) + "/" + Todays.getDate() + "/" + (Todays.getYear() - 100);
            document.getElementById("data").innerHTML = TheDate;
        }
        var timerID = null;
        var timerRunning = false;

        function startClock() 
        {
            stopclock();
            gettheDate();
            showtime();       
         }
        function stopclock() 
        {
            if (timerRunning) 
            {
                clearTimeout(timerID);
                timerRunning = false;
            }
        }


        function showtime() 
        {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            var timeValue = "" + ((hours > 12) ? hours - 12 : hours);
            timeValue += ((minutes < 10) ? ":0" : ":") + minutes;
            timeValue += ((seconds < 10) ? ":0" : ":") + seconds;
            timeValue += (hours >= 12) ? " P.M." : " A.M.";
            document.getElementById("zegarek").innerHTML = timeValue;
            timerID = setTimeout("showtime()", 1000);
            timerRunning = true;
        }

        $("#animacjaTestowa1").on("click", function()
        {
            $(this).animate({
                width: "500px",
                opacity: 0.4,
                fontsize: "3em",
                borderwidth: "10px"
            }, 1500);
        });
