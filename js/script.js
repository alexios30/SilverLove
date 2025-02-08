window.onload = () => {
    let elementCalendrier = document.getElementById("calendrier");

    let calendrier = new FullCalendar.Calendar(elementCalendrier, {
        plugins: ["dayGrid", "timeGrid", "list", "interaction"],
        defaultView: "timeGridWeek",
        locale: "fr",
        header: {
            left: "prev,next, today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,list",
        },
        buttonText: {
            today: "Aujourd'hui",
            month: "Mois",
            week: "Semaine",
            day: "Jour",
        },
        events: evenements,
        nowIndicator: true,
        firstDay: 1,
        minTime: "07:00:00",
        maxTime: "23:00:00",
        editable: true,
        eventClick: function (info) {
            // Set modal content
            document.getElementById("eventTitle").innerText = info.event.title;
            document.getElementById(
                "eventStart"
            ).innerText = `Start: ${info.event.start.toLocaleString()}`;
            document.getElementById("eventEnd").innerText = `End: ${
                info.event.end ? info.event.end.toLocaleString() : "N/A"
            }`;

            let modal = document.getElementById("eventModal");
            modal.style.display = "block";

            let span = document.getElementsByClassName("close")[0];
            span.onclick = function () {
                modal.style.display = "none";
            };
            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };
        },
    });
    calendrier.render();
};
