console.log("Archivo JavaScript cargado correctamente");

const API_BASE_URL_ESTUDIANTES = "http://localhost:8000/api/app/estudiante";

// Agregar estudiante
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
        console.error("Error al agregar estudiante:", response.statusText);
        throw new Error("Error al agregar estudiante");
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
