import { ComponentFixture, TestBed } from '@angular/core/testing';
import { RegistrarEmpresas } from './registrar-empresas';

describe('RegistrarEmpresas', () => {
  let component: RegistrarEmpresas;
  let fixture: ComponentFixture<RegistrarEmpresas>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RegistrarEmpresas],
    }).compileComponents();

    fixture = TestBed.createComponent(RegistrarEmpresas);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
