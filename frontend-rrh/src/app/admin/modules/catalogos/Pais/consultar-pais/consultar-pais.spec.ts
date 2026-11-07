import { ComponentFixture, TestBed } from '@angular/core/testing';
import { ConsultarPais } from './consultar-pais';

describe('ConsultarPais', () => {
  let component: ConsultarPais;
  let fixture: ComponentFixture<ConsultarPais>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ConsultarPais],
    }).compileComponents();

    fixture = TestBed.createComponent(ConsultarPais);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
