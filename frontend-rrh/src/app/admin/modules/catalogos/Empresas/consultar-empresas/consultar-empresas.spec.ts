import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ConsultarEmpresas } from './consultar-empresas';

describe('ConsultarEmpresas', () => {
  let component: ConsultarEmpresas;
  let fixture: ComponentFixture<ConsultarEmpresas>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ConsultarEmpresas],
    }).compileComponents();

    fixture = TestBed.createComponent(ConsultarEmpresas);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
