var modal = document.getElementById("update");
var close = document.getElementsByClassName("close")[0];
var appts = document.getElementsByClassName("appt");

var room = document
	.getElementsByClassName("room-co")[0]
	.getElementsByClassName("current")[0]
	.getAttribute("data-id");

window.onclick = function (event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}

close.onclick = function () {
	modal.style.display = "none";
}

for (var i = 0; i < appts.length; i++) {
	appts[i].onclick = function () {
		if (this.classList.contains("simple")) {
			var type = "simple";
		}

		if (this.classList.contains("recur")) {
			var type = "recurrent";
		}

		var id = this.getAttribute("data-id");
		var day = this.parentElement.getElementsByClassName("num")[0]
			.getAttribute("data-day");

		var url = "/appointment/popup"
			+ "/day-" + day
			+ "/type-" + type
			+ "/" + id;

		var xhr = new XMLHttpRequest();
		xhr.open("GET", url, false);
		xhr.send();

		if (xhr.status === 200) {
			var content = modal.getElementsByClassName("content")[0];
			content.innerHTML = xhr.responseText;
			modal.style.display = "block";
		}
	}
}
