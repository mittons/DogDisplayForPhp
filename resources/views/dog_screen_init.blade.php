<!DOCTYPE html>
<html>
<head>
    <title>🐶</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<style>
    body {
        margin: 0;
        font-family: 'Roboto', sans-serif;
    }

    md-item {
        font-family: 'Roboto', sans-serif;
    }

    #top-container {
        position: fixed;
        top: 0px;
        z-index: 10000;
        width: 100%;
    }

    .header-bar {
        box-sizing: border box;
        width: 100%;
        background-attachment: scroll;
        background-color: rgb(103, 58, 183);
        letter-spacing: 0.25px;
        font-weight: 550;
        font-size:20px;
        
        display: flex;
        flex-direction: row;
        align-items: center;
        padding-inline: 16px;
        
        font-family: 'Roboto', sans-serif;
        color: white;

    }

    .button-row {
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .button-container {
        padding: 9px 0 10px;
    }

    .pill {
        border-radius: 0.8rem;
        padding-inline: 0.9rem;
        padding-block: 0.45rem;
        --pill-accent: oklch(53.18% 0.28 296.97);
        background: color-mix(in srgb, var(--pill-accent) 15%, transparent);
        border: 1;
        color: var(--pill-accent);
        font-size: 0.900rem;
        font-weight: 500;

        padding-block: 0.45rem;
        padding-inline: 0.9rem;
    }
    
    #progress-spinner-container{
        padding-top: 130px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    #scrollable-list {
        display:block;
        padding-top: 120px;
        margin-inline: 6px;
    }

    .elevated-card-item {
        margin-bottom: 10px;
    }

    .card-item {
        display: flex;
        align-items: start;
        padding-block: 0px;
        padding-inline: 7px;
        min-height: fit-content;
    }

    .card-headline-text {
        line-height: 32px;
        font-weight: 500;
        font-size: 20px;
    }

    .card-supporting-text{
        line-height: 22px;
        color: rgba(0, 0, 0, 0.54);
        letter-spacing: 0.1px;
        font-weight: 550;
        font-family: 'Roboto', sans-serif;
    }

    @media screen and (min-width: 768px) {
        .header-bar {
            height: 64px; /* standard height */
        }
    }

    @media screen and (max-width: 767px) {
        .header-bar {
            height: 56px; /* mobile height */
        }
    }
</style>

<script type="module" src="{{ asset('js/bundle.js') }}"></script>
<body>
    <div id="top-container">
        <div class="header-bar">
            Dog Diversity Abundance! 🐶
        </div>
        <div class="button-row" style="background-color: white;">
            <div class="button-container">
                <button class="pill" id="dog-breed-list-request-button">Get list of so many types of good dogs!</button>
            </div>
        </div>
    </div>
    <div id="progress-spinner-container" style="display: none;">
        <md-circular-progress indeterminate style="--md-circular-progress-size: 40px;" ></md-circular-progress>
      </div>
    <div id="breed-list-container">
    </div>
    
	<!-- Snackbar Container -->
    <div id="snackbar" style="visibility: hidden; min-width: 250px; background-color: #333; color: #fff; text-align: center; border-radius: 2px; padding: 16px; position: fixed; z-index: 1; left: 50%; bottom: 30px; font-size: 17px; transform: translateX(-50%);">
        The application encountered an exception while trying to fetch data from external services. Please try again later.
    </div>
</body>

<script>
    let dogsLoaded = false;

    document.querySelector('.pill').addEventListener('click', function() {
    if (!dogsLoaded) {
        // Show loading spinner
        document.getElementById('progress-spinner-container').style.display = 'flex';
    }

    fetch('/renderBreeds')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            // Inject the HTML into the correct place in the document
            document.getElementById('breed-list-container').innerHTML = html;
            dogsLoaded = true;
            // Hide loading spinner
            document.getElementById('progress-spinner-container').style.display = 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            
            //Display user facing snackbar on error
            showSnackbar() 

            // Hide loading spinner
            document.getElementById('progress-spinner-container').style.display = 'none';
        });
    });

    function showSnackbar() {
        var snackbar = document.getElementById("snackbar");
        snackbar.style.visibility = "visible";
        setTimeout(function(){ snackbar.style.visibility = "hidden"; }, 6000);
    }

</script>

</html>