function callLoader(container) {
    loaderContainer = container;

    loaderContainer.append(' <div class="loaderOverlay">\n' +
        '            <div class="loaderObject">\n' +
        '                <svg width="80px"  height="80px"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="lds-ring">\n' +
        '                    <circle cx="50" cy="50" r="30" stroke="#ffffcb" stroke-width="10" fill="none"></circle>\n' +
        '                    <circle cx="50" cy="50" r="30" stroke="#ff7c81" stroke-width="10" fill="none" transform="rotate(144 50 50)">\n' +
        '                        <animateTransform attributeName="transform" type="rotate" calcMode="linear" values="0 50 50;360 50 50" keyTimes="0;1" dur="1s" begin="0s" repeatCount="indefinite"></animateTransform>\n' +
        '                        <animate attributeName="stroke-dasharray" calcMode="linear" values="18.84955592153876 169.64600329384882;94.2477796076938 94.24777960769377;18.84955592153876 169.64600329384882" keyTimes="0;0.5;1" dur="1" begin="0s" repeatCount="indefinite"></animate>\n' +
        '                    </circle>\n' +
        '                </svg>\n' +
        '            </div>\n' +
        '        </div>');
}
function removeLoader() {
    $('.loaderOverlay').remove();
}