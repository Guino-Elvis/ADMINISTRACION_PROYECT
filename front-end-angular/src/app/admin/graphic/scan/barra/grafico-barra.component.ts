import {
  Component,
  Input,
  SimpleChanges,
  ViewChild,
  ElementRef,
  inject
} from '@angular/core';

import {
  Chart,
  ChartConfiguration,
  BarElement,
  CategoryScale,
  LinearScale,
  Title,
  Tooltip,
  Legend,
  BarController
} from 'chart.js';

import { AttackTypeStat } from '../../../../models/scan-chart-data.model';

Chart.register(BarElement, CategoryScale, LinearScale, Title, Tooltip, Legend, BarController);
@Component({
  selector: 'app-grafico-barra',
  standalone: true,
  templateUrl: './grafico-barra.component.html',
  styles: ``
})
export class GraficoBarraComponent {
  @Input() attackTypes: AttackTypeStat[] = [];

  @ViewChild('barCanvas') barCanvas!: ElementRef<HTMLCanvasElement>;
  chart?: Chart;

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['attackTypes'] && this.attackTypes?.length) {
      this.crearGrafico(this.attackTypes);
    }
  }

  ngAfterViewInit(): void {
    if (this.attackTypes?.length) {
      this.crearGrafico(this.attackTypes);
    }
  }

  crearGrafico(attackTypes: AttackTypeStat[]): void {
    if (!this.barCanvas) return;
    if (this.chart) {
      this.chart.destroy();
    }

    const labels = attackTypes.map(a => a.type);
    const data = attackTypes.map(a => a.count);

    const config: ChartConfiguration<'bar'> = {
      type: 'bar',
      data: {
        labels,
        datasets: [
          {
            label: 'Cantidad por tipo de ataque',
            data,
            backgroundColor: '#2196F3',
            borderRadius: 5,
            barThickness: 20
          }
        ]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          title: {
            display: true,
            text: 'Ataques Realizados'
          },
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            title: {
              display: true,
              text: 'Cantidad'
            }
          },
          y: {
            title: {
              display: true,
              text: 'Tipo de Ataque'
            },
            ticks: {
              autoSkip: false,
              maxRotation: 0,
              minRotation: 0
            }
          }
        }
      }
    };

    this.chart = new Chart(this.barCanvas.nativeElement, config);
  }
}
