import { CommonModule } from '@angular/common';
import { Component, OnInit, AfterViewInit, ViewEncapsulation, ElementRef } from '@angular/core';
import { FormBuilder, FormGroup, ReactiveFormsModule, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { MessagesService } from '../../admin/services/messages/messages';
import { LoginService } from '../services/login';


declare const window: any;

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './login.html',
  styleUrl: './login.css',
  encapsulation: ViewEncapsulation.None
})
export class Login implements OnInit, AfterViewInit {
  protected formLogin!: FormGroup;
  protected mostrarPassword: boolean = false;

  constructor (
    private el:           ElementRef,
    private fb:           FormBuilder, 
    private loginService: LoginService,
    private router:       Router,
    private messages:     MessagesService
  ) {}

  ngOnInit(): void {
    this.crearFormLogin();
  }

  ngAfterViewInit(): void {
    setTimeout(() => {
      this.iniciarAnimacion();
    }, 150);
  }

  // ---------------------------------------------------------
  // LÓGICA DE BACKEND (Formulario, Validaciones, Auth)
  // ---------------------------------------------------------
  private crearFormLogin(): void {
    this.formLogin = this.fb.group({
      busines_mail: [null, [Validators.required, Validators.email, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-_:@#$%&+{}()?¿!¡\n]*')]],
      password:     [null, [Validators.required, Validators.pattern('[a-zA-Zá-úÁ-Ú0-9 .,-_:@#$%&+{}()?¿!¡\n]*')]]
    });
  }

  protected toggleMostrarPassword(): void {
    this.mostrarPassword = !this.mostrarPassword;
  }

  protected iniciarSesion(): void {
    if (this.formLogin.invalid) {
      this.messages.mensajeGenerico('Aún hay campos vacíos o que no cumplen con la estructura correcta',
        'info', 'Los campos requeridos están marcados con un *'
      );
      return;
    }

    const credenciales: any = {
      busines_mail: this.formLogin.value.busines_mail, 
      password:     this.formLogin.value.password,
    };

    this.messages.mensajeEsperar();

    this.loginService.login(credenciales).toPromise().then(
      (respuesta: any) => {
        if(respuesta.success === 204 || respuesta.success === false) {
          this.messages.mensajeGenerico(
            respuesta.mensaje, 
            'warning', 
            respuesta.title
          );
          return;
        }

        // Guarda el token con el nombre exacto que espera tu interceptor
        if (respuesta.token) {
          localStorage.setItem('token_orden_gitlatam', respuesta.token);
          if(respuesta.usuarios) localStorage.setItem('usuario', JSON.stringify(respuesta.usuarios));
          if(respuesta.permisos) localStorage.setItem('permisos', JSON.stringify(respuesta.permisos));
        }

        this.messages.cerrarMensajes();
        
        // Redirección adaptada a la ruta home
        this.router.navigate(['/admin']);
      },
      error => {
        this.messages.cerrarMensajes();
        this.messages.mensajeGenerico(
          'Ocurrio un error al Iniciar Sesión',
          'error'
        );
      }
    );
  }

  // ---------------------------------------------------------
  // LÓGICA DE ANIMACIÓN FRONTEND (GSAP)
  // ---------------------------------------------------------
  private iniciarAnimacion(): void {
    const { gsap, MorphSVGPlugin, Draggable } = window;
    const root = this.el.nativeElement;

    gsap.set(root.querySelector(".lamp"), { display: "block" });

    if (!gsap || !Draggable) return;
    if (MorphSVGPlugin) gsap.registerPlugin(MorphSVGPlugin);

    const ON = root.querySelector("#on");
    const OFF = root.querySelector("#off");
    const LOGIN_FORM = root.querySelector(".login-form");
    
    const PROXY = document.createElement("div");
    PROXY.style.cssText = "position:absolute; visibility:hidden; width:1px; height:1px;";
    root.appendChild(PROXY);

    const CORDS = gsap.utils.toArray(".cords path", root);
    const CORD_DURATION = 0.1;
    const HIT = root.querySelector(".lamp__hit");
    const DUMMY_CORD = root.querySelector(".cord--dummy");
    
    const ENDX_STR = DUMMY_CORD.getAttribute("x2") || "124";
    const ENDY_STR = DUMMY_CORD.getAttribute("y2") || "348";
    const ENDX = parseInt(ENDX_STR, 10);
    const ENDY = parseInt(ENDY_STR, 10); 

    const STATE = { ON: false };

    gsap.set([".cords", HIT], { x: -10 });
    gsap.set(".lamp__eye", { rotate: 180, transformOrigin: "50% 50%", yPercent: 50 });

    const CORD_TL = gsap.timeline({
        paused: true,
        onStart: () => {
            STATE.ON = !STATE.ON;
            gsap.set(document.documentElement, { "--on": STATE.ON ? 1 : 0 });
            
            const hue = gsap.utils.random(0, 359);
            gsap.set(document.documentElement, { 
                "--shade-hue": hue,
                "--glow-color": `hsl(${hue}, 40%, 45%)`,
                "--glow-color-dark": `hsl(${hue}, 40%, 35%)`,
            });

            gsap.set(".lamp__eye", { rotate: STATE.ON ? 0 : 180 });
            gsap.set([DUMMY_CORD, HIT], { display: "none" });
            gsap.set(CORDS[0], { display: "block" });
            
            if (STATE.ON) {
                if(ON) ON.checked = true;
                if(OFF) OFF.checked = false;
                LOGIN_FORM?.classList.add("active");
            } else {
                if(ON) ON.checked = false;
                if(OFF) OFF.checked = true;
                LOGIN_FORM?.classList.remove("active");
            }
        },
        onComplete: () => {
            gsap.set([DUMMY_CORD, HIT], { display: "block" });
            gsap.set(CORDS[0], { display: "none" });
        },
    });

    CORD_TL.to(PROXY, { duration: CORD_DURATION });

    if (MorphSVGPlugin && CORDS.length > 1) {
        for (let i = 1; i < CORDS.length; i++) {
            CORD_TL.add(
                gsap.to(CORDS[0], {
                    morphSVG: CORDS[i],
                    duration: CORD_DURATION,
                    repeat: 1,
                    yoyo: true,
                }),
                "<" 
            );
        }
    }

    Draggable.create(PROXY, {
        trigger: HIT,
        type: "x,y", 
        onDrag: function () {
            gsap.set(DUMMY_CORD, {
                attr: { 
                    x2: ENDX + this.x, 
                    y2: Math.max(400, ENDY + this.y) 
                },
            });
        },
        onRelease: function () {
            const distanciaTotal = Math.sqrt((this.x * this.x) + (this.y * this.y));
            
            gsap.to(DUMMY_CORD, {
                attr: { x2: ENDX, y2: ENDY },
                duration: 0.6,
                ease: "elastic.out(1, 0.3)",
                onComplete: () => {
                    if (distanciaTotal > 30) {
                        CORD_TL.restart();
                    }
                    gsap.set(PROXY, { x: 0, y: 0 }); 
                },
            });
        },
    });
  }
}