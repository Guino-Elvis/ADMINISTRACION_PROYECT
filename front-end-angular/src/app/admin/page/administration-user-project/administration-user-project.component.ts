import { CommonModule } from '@angular/common';
import { Component, ViewChild } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { LayoutComponent } from '../../partials/layout.component';
import { CustomLinkComponent } from '../../../components/custom-link.component';
import { ModalFormComponent } from '../../../components/modal/modal-form.component';
import { CustomDatepickerComponent } from '../../../components/custom-datepicker/custom-datepicker.component';
import { ReusableButtonComponent } from '../../../components/reusable-button.component';
import { FontAwesomeModule } from '@fortawesome/angular-fontawesome';
import { ItemUpdatedAtComponent } from '../../../components/table/item-updated-at.component';
import { PaginationComponent } from '../../../components/table/pagination/pagination.component';
import { NoItemPageComponent } from '../../../components/no-item-page.component';
import { ICONS } from '../../../core/icons';
import { AdministrationUserProject } from '../../../models/administrationUserProject.model';
import { AlertService } from '../../../services/alert.service';
import { first } from 'rxjs';
import { UserProjectService } from '../../../services/user-project.service';
import { AdministrationUserProjectFormComponent } from '../../form/administration-user-project/administration-user-project-form.component';

@Component({
  selector: 'app-administration-user-project',
   standalone: true, 
  imports: [
    FormsModule,
    CommonModule,
    LayoutComponent,
    CustomLinkComponent,
    AdministrationUserProjectFormComponent,
    ModalFormComponent,
    CustomDatepickerComponent,
    ReusableButtonComponent,
    FontAwesomeModule,
    ItemUpdatedAtComponent,
    PaginationComponent,
    NoItemPageComponent
  ],
  templateUrl: './administration-user-project.component.html',
  styles: ``
})
export class AdministrationUserProjectComponent {
  @ViewChild(CustomDatepickerComponent) datepicker!: CustomDatepickerComponent;
  @ViewChild(AdministrationUserProjectFormComponent) administrationUserProjectFormComponent!: AdministrationUserProjectFormComponent;

  icons = ICONS;
  administrationUserProjects: AdministrationUserProject[] = [];
  searchText: string = '';
  startDate: string | null = null;
  endDate: string | null = null;
  sortOrder: 'asc' | 'desc' = 'desc';
  perPage: number = 8;
  currentPage: number = 1;
  totalPages: number = 1;
  totalItems: number = 0;
  administrationUserProjectForm: AdministrationUserProject = {
    id: 0,
    status: '',
    role: '',
    name: '',
    email: '',
    password: '',
    project_id: 0,

    created_at: null,
    updated_at: null
  };
  editMode: boolean = false;
  editId: number | null = null;
  modalOpen: boolean = false;

  constructor(
    private alertService: AlertService,
    private administrationUserProjectService: UserProjectService
  ) { }
  ngOnInit() {
    this.loadAdministrationUserProjects();
  }
  loadAdministrationUserProjects() {
    this.administrationUserProjectService.getAdministrationUserProjects(
      this.searchText,
      this.startDate,
      this.endDate,
      this.sortOrder,
      this.perPage,
      this.currentPage
    ).pipe(first()).subscribe({
      next: (response) => {
        if (Array.isArray(response.data)) {
          this.administrationUserProjects = response.data;
          this.totalItems = response.total;
          this.totalPages = Math.ceil(this.totalItems / this.perPage);
        } else {
          console.error('La respuesta no contiene un array de usuarios');
        }
      },
      error: (error) => console.error('Error al obtener usuarios:', error),
    });
  }

  filterAdministrationUserProjects() {
    if (this.searchText.trim().length < 3) {
      return;
    }
    this.loadAdministrationUserProjects();
  }

  updateDates(dates: { startDate: string | null, endDate: string | null }) {
    this.startDate = dates.startDate;
    this.endDate = dates.endDate;
    this.loadAdministrationUserProjects();
  }

  toggleSortOrder(): void {
    this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
    this.loadAdministrationUserProjects();
  }

  resetFilters(): void {
    this.searchText = '';
    this.startDate = null;
    this.endDate = null;
    this.sortOrder = 'desc';
    this.perPage = 8;
    this.currentPage = 1;
    this.datepicker.reset();
    this.loadAdministrationUserProjects();
  }

  hasActiveFilters(): boolean {
    return this.searchText.trim().length > 0;
  }

  changePage(page: number): void {
    if (page > 0 && page <= this.totalPages) {
      this.currentPage = page;
      this.loadAdministrationUserProjects();
    }
  }

  updateItemsPerPage(perPage: number): void {
    this.perPage = perPage;
    this.loadAdministrationUserProjects();

  }

  createAdministrationUserProject(): void {
    this.editMode = false;
    this.administrationUserProjectForm = {
      id: 0,
      status: '',
      role: '',
      name: '',
      email: '',
      password: '',
      project_id: 0,

      created_at: null,
      updated_at: null
    };
    this.modalOpen = true;
  }

  editAdministrationUserProject(administrationUserProject: AdministrationUserProject): void {
    if (administrationUserProject.id !== undefined) {
      this.editMode = true;
      this.editId = administrationUserProject.id;
      this.administrationUserProjectForm = { ...administrationUserProject };
      this.modalOpen = true;
    } else {
      console.error('El usuario no tiene un ID válido');
    }
  }

  saveAdministrationUserProject(administrationUserProjectData: AdministrationUserProject): void {
    if (this.editMode && this.editId !== null) {

      this.administrationUserProjectService.updateAdministrationUserProject(this.editId, administrationUserProjectData).pipe(first()).subscribe({
        next: () => {
          this.loadAdministrationUserProjects();
          this.closeModal();
          this.alertService.showSuccess('Usuario actualizado correctamente');
        },
        error: (error) => {
          this.alertService.showError('Hubo un error al actualizar');
        },
      });
    } else {
      this.administrationUserProjectService.createAdministrationUserProject(administrationUserProjectData).pipe(first()).subscribe({
        next: () => {
          this.loadAdministrationUserProjects();
          this.closeModal();
          this.alertService.showSuccess('Usuario creado correctamente');
        },
        error: () => {
          this.alertService.showError('Hubo un error al crear');
        },
      });
    }
  }

  deleteAdministrationUserProject(id: number): void {
    this.alertService.confirmDelete().then((confirmed: boolean) => {
      if (confirmed) {
        this.administrationUserProjectService.deleteAdministrationUserProject(id).pipe(first()).subscribe({
          next: () => {
            this.alertService.showSuccess('Usuario eliminado correctamente');
            this.administrationUserProjects = this.administrationUserProjects.filter(cat => cat.id !== id);
          },
          error: () => {
            this.alertService.showError('Hubo un error al eliminar');
          },
        });
      }
    });
  }

  closeModal(): void {
    this.modalOpen = false;
    this.resetForm();
  }

  resetForm(): void {
    this.administrationUserProjectForm = {
      id: 0,
      status: '',
      role: '',
      name: '',
      email: '',
      password: '',
      project_id: 0,
      created_at: null,
      updated_at: null
    };
    this.editMode = false;
    this.editId = null;
  }
  getRoleNames(administrationUserProject: AdministrationUserProject): string {
    return administrationUserProject.roles?.map(r => r.name).join(', ') || 'Sin roles';
  }

  getProjectNames(administrationUserProject: AdministrationUserProject): string {
    return administrationUserProject.projects?.map(c => c.name).join(', ') || 'Sin empresas';
  }
}
