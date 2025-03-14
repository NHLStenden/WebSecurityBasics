document.addEventListener("DOMContentLoaded", function () {
    const list = document.getElementById("todo-list");

    fetch("http://localhost:8081/")
        .then(response => response.json())
        .then(data => {
            data.forEach(todo => {
                let li = document.createElement("li");
                li.textContent = todo.task;
                list.appendChild(li);
            });
        })
        .catch(error => console.error("Fout bij ophalen:", error));
});
