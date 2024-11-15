/*Muestra el menu en pantallas pequeñas*/
const IconoBtn = document.querySelector('.icono_menu')
        const IconoBtnI = document.querySelector('.icono_menu i')
        const MirarMenu = document.querySelector('.mostrar-menu')

        IconoBtn.onclick = function (){
            MirarMenu.classList.toggle('open')
            const isOpen = MirarMenu.classList.contains('open')
        
            IconoBtnI.classList = isOpen
            ? 'bx bx-menu'
            : 'bx bx-menubx bx-menu'
        }


/*preguntas frecuentes */
var acc = document.getElementsByClassName("accordion");
      var i;

      for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function () {
          this.classList.toggle("active");
          this.parentElement.classList.toggle("active");

          var pannel = this.nextElementSibling;

          if (pannel.style.display === "block") {
            pannel.style.display = "none";
          } else {
            pannel.style.display = "block";
          }
        });
      }