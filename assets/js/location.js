let locationReady = false;

function getLocation() {
    const input    = document.getElementById('locationInput');
    const latField = document.getElementById('latitudeInput');
    const lngField = document.getElementById('longitudeInput');
    const btn      = document.querySelector('button[onclick="getLocation()"]');

    if (!navigator.geolocation) {
        input.value = "Géolocalisation non supportée.";
        return;
    }

    locationReady = false;
    input.value   = "Localisation en cours...";
    if (btn) btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            latField.value = lat;
            lngField.value = lng;
            locationReady  = true;

            if (btn) btn.disabled = false;

            // Reverse geocode for human-readable address (display only)
            fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`)
                .then(res => res.json())
                .then(data => {
                    input.value = data.display_name || `${lat}, ${lng}`;
                })
                .catch(() => {
                    input.value = `${lat}, ${lng}`;
                });
        },
        function (error) {
            const messages = {
                1: "Permission refusée. Autorisez la localisation.",
                2: "Position introuvable.",
                3: "Délai dépassé, réessayez."
            };
            input.value   = messages[error.code] || "Erreur de localisation.";
            latField.value = '';
            lngField.value = '';
            locationReady  = false;
            if (btn) btn.disabled = false;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );

    
}

// Block form submission until coords are ready
document.addEventListener('DOMContentLoaded', function () {
    getLocation();

    const form = document.querySelector('form');
    form.addEventListener('submit', function (e) {
        const lat = document.getElementById('latitudeInput').value;
        const lng = document.getElementById('longitudeInput').value;

        if (!lat || !lng) {
            e.preventDefault();
            alert("Position GPS non encore disponible. Patientez ou cliquez sur 'Actualiser ma position'.");
            return;
        }
    });
});
