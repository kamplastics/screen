<!DOCTYPE html>
<html>
    <head>
        <title>Shop Floor Monitor</title>
        <style>
            html, body {
                margin: 0;
                padding: 0;
                height: 100%;
                width: 100%;
                overflow: hidden;
                background-image: "../../assets/images/transparent_textures/grey-jean.png";
            }
            img {
                height: 85%;
                width: 100%;
            }
            #last_update_time 
            {
                height: 10%;
                font-size: 3vw; /* Adjust the font size relative to viewport width */
                text-align: center;
                margin: 0;
                padding-top: 10px;
            }
            #img_uuid
            {
                height: 5%;
                font-size: 1vw; /* Adjust the font size relative to viewport width */
                text-align: center;
                margin: 0;
                padding-top: 10px;
            }
        </style>
    </head>
    <body>
        <h1 id="informative" onclick="goFullScreen()">Click the image or here to go full-screen</h1>
        <img id="shopImage" src="image.bmp" alt="Paul's Screen" title="Paul's image" onclick="goFullScreen()">
        <iframe id="shopIframe" src="http://10.20.0.219/" style="height: 85%; width: 100%; border: none; margin: 0; padding: 0;" onclick="goFullScreen()" hidden></iframe>
        <h2 id="last_update_time" style="height: 10%;">&nbsp;</h1>
        <h2 id="img_uuid" style="heigh: 10%;">&nbsp;</h5>
        <script>
            function rename_the_file_anyway()
            {
                xhr = new XMLHttpRequest();
                xhr.open('GET', 'rename_the_file_anyway.php', true);
                xhr.send();
            }
            
            reloadImage();
            function goFullScreen() 
            {
                const element = document.documentElement;

                if (element.requestFullscreen) 
                {
                    element.requestFullscreen().then
                    (
                        () => 
                        {
                            // Full-screen request successful
                        }
                    ).catch
                    (
                        (error) => 
                        {
                            console.error('Failed to enter full-screen:', error);
                        }
                    );
                } 
                else if (element.mozRequestFullScreen) 
                {
                    element.mozRequestFullScreen();
                } 
                else if (element.webkitRequestFullscreen) 
                {
                    element.webkitRequestFullscreen();
                } 
                else if (element.msRequestFullscreen) 
                {
                    element.msRequestFullscreen();
                }
                document.getElementById('informative').hidden = true;
            }
            
            function checkImageUpdate()  // this happens every minute
            {
                console.log("checkImageUpdate");
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'how_long_ago_was_the_image_last_updated.php', true);

                xhr.onload = function() 
                {
                    if (this.status === 200) 
                    {
                        const responseTime = parseInt(this.responseText, 10);

                        const imgElement = document.getElementById('shopImage');
                        const iframeElement = document.getElementById('shopIframe');
                        if (responseTime < 300) // for the first 5 minutes of the image's modification time
                        {
                            reloadImage();
                        }
                        
                        if (responseTime > 57600) // every 16 hours 
                        {
                            imgElement.hidden = true;
                            iframeElement.hidden = false;
                        } 
                        else 
                        {
                            imgElement.hidden = false;
                            iframeElement.hidden = true;
                        }
                        document.getElementById('last_update_time').innerHTML = 'Last updated ' + secondsToHumanReadable(responseTime) + ' ago';
                    }
                };
                xhr.send();
            }
            function reloadImage() // this happens every minute for the first 5 minutes of the image's modification time
            {
                fetchLatestImageName();
            }
            
            function secondsToHumanReadable(secs) 
            {
                const hours = Math.floor(secs / 3600);
                const minutes = Math.floor((secs % 3600) / 60);

                let timeString = "";
                if (hours > 0) 
                {
                    timeString += hours + " hour" + (hours > 1 ? "s" : "");
                }
                if (minutes > 0) 
                {
                    if (timeString.length > 0) 
                    {
                        timeString += " and ";
                    }
                    timeString += minutes + " minute" + (minutes > 1 ? "s" : "");
                }
                return timeString.length > 0 ? timeString : "0 minutes";
            }
            
            function updateImageSource(newImageName) 
            {
                console.log("updateImageSource");
                let imageElement = document.getElementById('shopImage');
                imageElement.src = 'image/' + newImageName;
                document.getElementById('img_uuid').innerHTML = newImageName;
            }
            
            function fetchLatestImageName() 
            {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_latest_image.php', true);

                xhr.onload = function() 
                {
                    if (this.status === 200) 
                    {
                        updateImageSource(this.responseText);
                    }
                };
                xhr.send();
            }

            // Check every minute (60000 milliseconds)
            setInterval(checkImageUpdate, 60000);

            // Initial check
            checkImageUpdate();
        </script>
    </body>
</html>
