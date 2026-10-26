import { Component } from '@angular/core';
import { Sidenav } from '../components/sidenav/sidenav';
import { RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [Sidenav, RouterOutlet],
  templateUrl: './home.html',
  styleUrl: './home.css',
})
export class Home {


}