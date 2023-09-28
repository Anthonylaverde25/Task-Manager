(function () {
  obtenerTareas();
  let tareas = [];
  let tareasFiltradas = [];

  const nuevaTareaBtn = document.querySelector("#agregar-tarea");
  nuevaTareaBtn.addEventListener("click", function () {
    mostrarFormulario(false);
  });

  //  FILTROS BUSQUEDA
  const filtros = document.querySelectorAll('#filtros input[type="radio');
  filtros.forEach((radio) => {
    radio.addEventListener("click", filtrarTareas);
  });

  function filtrarTareas(e) {
    const filtro = e.target.value;

    if (filtro !== "") {
      tareasFiltradas = tareas.filter((tarea) => tarea.estado === filtro);

      console.log(tareasFiltradas);
    } else {
      tareasFiltradas = [];
      console.log(tareasFiltradas);
    }
    mostrarTareas();
  }

  async function obtenerTareas() {
    try {
      const id = obtenerProyecto();
      const url = `/api/tareas?id=${id}`;
      const respuesta = await fetch(url);

      const resultado = await respuesta.json();
      tareas = resultado.tareas;
      mostrarTareas();
    } catch (error) {
      console.log(error);
    }
  }

  function mostrarTareas() {
    const arrayTareas = tareasFiltradas.length ? tareasFiltradas : tareas;
    limpiarTareas();

    totalPendientes();
    totalCompletas();

    if (arrayTareas.length === 0) {
      const contenedorTareas = document.querySelector("#listado-tareas");
      const textoNoTareas = document.createElement("LI");
      textoNoTareas.textContent = "No hay tareas";
      textoNoTareas.classList.add("no-tareas");

      contenedorTareas.appendChild(textoNoTareas);

      return;
    }

    const estado = {
      0: "Pendiente",
      1: "Completo",
    };

    arrayTareas.forEach((tarea) => {
      const contenedorTarea = document.createElement("LI");
      contenedorTarea.dataset.tareaId = tarea.id;
      contenedorTarea.classList.add("tarea");

      const nombreTarea = document.createElement("P");
      nombreTarea.textContent = tarea.nombre;
      nombreTarea.ondblclick = function () {
        mostrarFormulario(true, { ...tarea });
      };

      const opcionesDiv = document.createElement("DIV");
      opcionesDiv.classList.add("opciones");

      const btnEstadoTarea = document.createElement("BUTTON");
      btnEstadoTarea.classList.add("estado-tarea");
      btnEstadoTarea.classList.add(`${estado[tarea.estado].toLowerCase()}`);
      btnEstadoTarea.textContent = estado[tarea.estado];
      btnEstadoTarea.dataset.estadoTarea = tarea.estado;
      btnEstadoTarea.ondblclick = function () {
        cambiandoEstadoTarea({ ...tarea });
      };

      const btnEliminarTarea = document.createElement("BUTTON");
      btnEliminarTarea.classList.add("eliminar-tarea");
      btnEliminarTarea.dataset.tareaid = tarea.id;
      btnEliminarTarea.textContent = "Eliminar tarea";
      btnEliminarTarea.ondblclick = function () {
        confirmarEliminarTarea({ ...tarea });
      };

      opcionesDiv.appendChild(btnEstadoTarea);
      opcionesDiv.appendChild(btnEliminarTarea);

      contenedorTarea.appendChild(nombreTarea);
      contenedorTarea.appendChild(opcionesDiv);

      const listadoTarea = document.querySelector("#listado-tareas");
      listadoTarea.appendChild(contenedorTarea);
    });
  }

  function totalPendientes() {
    const pendienteRadio = document.querySelector("#pendientes");
    const totalPendientes = tareas.filter((tarea) => tarea.estado === "0");

    if (totalPendientes.length === 0) {
      pendienteRadio.disabled = true;
    } else {
      pendienteRadio.disabled = false;
    }
  }

  function totalCompletas() {
    const completaRadio = document.querySelector("#completadas");
    const completas = tareas.filter((tarea) => tarea.estado === "1");

    if (completas.length === 0) {
      completaRadio.disabled = true;
    } else {
      completaRadio.disabled = false;
    }
  }

  function mostrarFormulario(editar = false, tarea = {}) {
    let { nombre } = tarea;
    const modal = document.createElement("DIV");

    modal.classList.add("modal");
    modal.innerHTML = `
    <form class="formulario nueva-tarea">
    <fieldset>
      <legend>${editar ? "Editar tarea" : "Añadir nueva tarea"}</legend>
      <div class="campo">
        <label>Tarea</label>
        <input
          type="text"
          name="tarea"
          placeholder="${
            editar ? "Edita tu tarea" : "Añadir Tarea al proyecto actual"
          }"
          id="tarea"
          value = "${tarea.nombre ? tarea.nombre : ""}"
        >
      </div>
      <div class="opciones">
        <input type="submit" class="submit-nueva-tarea" value="${
          editar ? "Guardar cambios" : "Añadir Tarea"
        }">
        <button type="button" class="cerrar-modal">Cancelar</button>
      </div>
    </fieldset>
    </form>  
    `;

    setTimeout(() => {
      const formulario = document.querySelector(".formulario");
      formulario.classList.add("animar");
    }, 0);

    modal.addEventListener("click", (e) => {
      e.preventDefault();

      if (e.target.classList.contains("cerrar-modal")) {
        const formulario = document.querySelector(".formulario");
        formulario.classList.add("cerrar");

        setTimeout(() => {
          modal.remove();
        }, 400);
      }

      if (e.target.classList.contains("submit-nueva-tarea")) {
        const nombreTarea = document.querySelector("#tarea").value.trim();
        if (tarea === "") {
          const referencia = document.querySelector(".formulario legend");
          let mensaje = "El nombre de la tarea es obligatorio";
          let tipo = "error";

          mostrarAlerta(mensaje, tipo, referencia);
          return;
        }

        if (editar) {
          tarea.nombre = nombreTarea;
          actualizarTarea(tarea);
        } else {
          agregarTarea(nombreTarea);
        }
      }
    });

    document.querySelector(".dashboard").appendChild(modal);
  }

  function mostrarAlerta(mensaje, tipo, referencia) {
    const alertaPrevia = document.querySelector(".alerta");
    if (alertaPrevia) {
      alerta.remove();
    }

    const alerta = document.createElement("DIV");
    alerta.classList.add("alerta", tipo);
    alerta.textContent = mensaje;

    referencia.parentElement.insertBefore(
      alerta,
      referencia.nextElementSibling
    );

    setTimeout(() => {
      alerta.remove();
    }, 3000);
  }

  // CONSULTA AL SERVIDOR PARA AÑADIR UNA NUEVA TAREA
  async function agregarTarea(tarea) {
    // Creacion de  un objeto FormData para enviar datos al servidor
    const datos = new FormData();
    datos.append("nombre", tarea);
    datos.append("proyectoId", obtenerProyecto());

    try {
      // REALIZACION DE UNA PETICION FETCH POST AL SERVIDOR PHP
      const url = "http://localhost:3000/api/tarea";
      const respuesta = await fetch(url, {
        method: "POST",
        body: datos,
      });

      // PARSING DE LA RESPUESTA DEL SERVIDOR COMO A JSON
      const resultado = await respuesta.json();

      mostrarAlerta(
        resultado.mensaje,
        resultado.tipo,
        document.querySelector(".formulario legend")
      );

      if (resultado.tipo === "exito") {
        const modal = document.querySelector(".modal");
        mostrarAlerta(
          resultado.mensaje,
          resultado.tipo,
          document.querySelector(".formulario legend")
        );

        setTimeout(() => {
          modal.remove();
        }, 3000);

        //Agregar el objeto de tarea (LA TAREA QUE SE AGREGARA) a la global tareas
        const tareaObj = {
          id: String(resultado.id),
          nombre: tarea,
          estado: "0",
          proyectoId: resultado.proyectoId,
        };

        tareas = [...tareas, tareaObj];
        mostrarTareas();
        //console.log(tareaObj);
      }

      //console.log(resultado);
    } catch (error) {
      console.log(error);
    }
  }

  function obtenerProyecto() {
    const proyectoParams = new URLSearchParams(window.location.search);
    const proyecto = Object.fromEntries(proyectoParams);
    return proyecto.id;
  }

  function cambiandoEstadoTarea(tarea) {
    tarea.estado === 1 ? (tarea.estado = 0) : 0;
    const nuevoEstado = tarea.estado === "1" ? "0" : "1";
    tarea.estado = nuevoEstado;

    actualizarTarea(tarea);
  }

  // ES EL QUE SE ENVIARA AL SERVIDOR
  async function actualizarTarea(tarea) {
    //console.log(tarea);
    const { id, nombre, estado, proyectoId } = tarea;
    const datos = new FormData();
    datos.append("id", id);
    datos.append("nombre", nombre);
    datos.append("estado", estado);
    datos.append("proyectoId", obtenerProyecto());

    try {
      const url = "http://localhost:3000/api/tarea/actualizar";
      const respuesta = await fetch(url, {
        method: "POST",
        body: datos,
      });

      const resultado = await respuesta.json();

      if (resultado.respuesta.tipo === "exito") {
        Swal.fire(
          resultado.respuesta.mensaje,
          resultado.respuesta.mensaje,
          "success"
        );

        const modal = document.querySelector(".modal");
        if (modal) {
          modal.remove();
        }

        tareas.map((tareaEnMemoria) => {
          if (tareaEnMemoria.id === tarea.id) {
            tareaEnMemoria.estado = estado;
            tareaEnMemoria.nombre = nombre;
          }

          return tareaEnMemoria;
        });
        mostrarTareas();
      }
    } catch (error) {
      console.log(error);
    }
  }

  function confirmarEliminarTarea(tarea) {
    Swal.fire({
      title: "Eliminar tarea?",
      showCancelButton: true,
      confirmButtonText: "Si",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        eliminarTarea(tarea);
      }
    });
  }

  async function eliminarTarea(tarea) {
    const { id, nombre, estado, proyectoId } = tarea;
    const dato = new FormData();
    dato.append("id", id);
    dato.append("nombre", nombre);
    dato.append("estado", estado);
    dato.append("proyectoId", obtenerProyecto());

    try {
      const url = "http://localhost:3000/api/tarea/eliminar";
      const respuesta = await fetch(url, {
        method: "POST",
        body: dato,
      });

      const resultado = await respuesta.json();
      if (resultado.resultado) {
        /*
        mostrarAlerta(
          resultado.mensaje,
          resultado.tipo,
          document.querySelector(".contenedor-nueva-tarea")
        );*/

        Swal.fire("Eliminado", resultado.mensaje, "success");

        tareas = tareas.filter(
          (tareasEnMemoria) => tareasEnMemoria.id !== tarea.id
        );
        mostrarTareas();
      }

      //console.log(resultado);
    } catch (error) {}
  }

  function limpiarTareas() {
    const listadoTareas = document.querySelector("#listado-tareas");
    while (listadoTareas.firstChild) {
      listadoTareas.removeChild(listadoTareas.firstChild);
    }
  }
})();
