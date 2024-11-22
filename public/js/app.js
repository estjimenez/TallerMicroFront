const API_BASE_URL_ESTUDIANTES = "http://localhost:8000/api/app/estudiante";

// Manejo de formulario para agregar estudiantes
document.getElementById("addStudentForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const cod = document.getElementById("cod").value;
  const nombres = document.getElementById("nombres").value;
  const email = document.getElementById("email").value;

  fetch(API_BASE_URL_ESTUDIANTES, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ cod, nombres, email }),
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al agregar estudiante: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      if (data.status === 201) {
        alert("Estudiante agregado correctamente");
        document.getElementById("addStudentForm").reset();
        loadStudents();
      } else {
        console.error(data);
        alert(data.message || "Error al agregar el estudiante");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error de conexión al servidor");
    });
});

// Cargar estudiantes al hacer clic en el botón
document.getElementById("listStudentsBtn").addEventListener("click", loadStudents);

function loadStudents() {
  fetch(API_BASE_URL_ESTUDIANTES)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al cargar estudiantes: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      const studentsTableBody = document.querySelector("#studentsTable tbody");
      studentsTableBody.innerHTML = "";
      if (Array.isArray(data) && data.length === 0) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");
        cell.colSpan = 4;
        cell.textContent = "No hay estudiantes registrados";
        row.appendChild(cell);
        studentsTableBody.appendChild(row);
        return;
      }
      data.forEach((student) => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${student.cod}</td>
          <td>${student.nombres}</td>
          <td>${student.email}</td>
          <td>
            <button onclick="deleteStudent('${student.cod}')">Eliminar</button>
          </td>
        `;
        studentsTableBody.appendChild(row);
      });
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error al cargar los estudiantes");
    });
}

function deleteStudent(cod) {
  if (!confirm("¿Estás seguro de eliminar este estudiante?")) return;

  fetch(`${API_BASE_URL_ESTUDIANTES}/${cod}`, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al eliminar el estudiante: " + response.statusText);
      }
      return response.json();
    })
    .then((data) => {
      if (data.status === 200) {
        alert("Estudiante eliminado correctamente");
        loadStudents();
      } else {
        alert(data.message || "Error al eliminar el estudiante");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("Error de conexión al servidor");
    });
}

// Cargar estudiantes cuando se cargue la página
document.addEventListener('DOMContentLoaded', loadStudents);
