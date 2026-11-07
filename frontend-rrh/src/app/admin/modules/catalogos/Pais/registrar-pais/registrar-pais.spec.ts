import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RegistrarPais } from './registrar-pais';

describe('RegistrarPais', () => {
  let component: RegistrarPais;
  let fixture: ComponentFixture<RegistrarPais>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RegistrarPais],
    }).compileComponents();

    fixture = TestBed.createComponent(RegistrarPais);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
