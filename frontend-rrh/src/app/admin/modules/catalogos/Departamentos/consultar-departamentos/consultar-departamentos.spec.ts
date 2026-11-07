import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ConsultarDepartamentos } from './consultar-departamentos';

describe('ConsultarDepartamentos', () => {
  let component: ConsultarDepartamentos;
  let fixture: ComponentFixture<ConsultarDepartamentos>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ConsultarDepartamentos],
    }).compileComponents();

    fixture = TestBed.createComponent(ConsultarDepartamentos);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
