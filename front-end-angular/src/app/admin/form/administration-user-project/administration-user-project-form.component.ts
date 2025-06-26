import { CommonModule } from '@angular/common';
import { Component, EventEmitter, Input, Output, SimpleChanges } from '@angular/core';
import { FormBuilder, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { Subscription } from 'rxjs';
import { ApiService } from '../../../services/api.service';
import { RoleService } from '../../../services/role.service';
import { Role } from '../../../models/role.model';
import { User } from '../../../models/user.model';
import { AuthService } from '../../../services/auth.service';
import { Project } from '../../../models/project.model';
import { ProjectService } from '../../../services/project.service';

@Component({
  selector: 'app-administration-user-project-form',
   standalone: true, // ✅ <--- ESTO ES LO QUE FALTABA
  imports: [CommonModule, ReactiveFormsModule, FormsModule],
  templateUrl: './administration-user-project-form.component.html',
  styles: ``
  
})
export class AdministrationUserProjectFormComponent {
  @Input() administrationUserProject: any = null;
  @Output() onSave = new EventEmitter<any>();
  @Output() onClose = new EventEmitter<void>();

  administrationUserProjectForm: FormGroup;
  projectId: number = 0;
  private projectSubscription: Subscription = new Subscription();
  private roleSubscription: Subscription = new Subscription();
  private userSubscription: Subscription = new Subscription();

  statusOptions = [
    { id: '2', nombre: 'Activo' },
    { id: '1', nombre: 'Inactivo' },
  ];

  roleOptions: Role[] = [];
  projectOptions: Project[] = [];
  userData: User | null = null;
  constructor(
    private fb: FormBuilder,
    private roleService: RoleService,
    private projectService: ProjectService,
    public apiService: ApiService,
  ) {
    this.administrationUserProjectForm = this.fb.group({
      role: ['', Validators.required],
      name: ['', Validators.required],
      email: ['', [Validators.required]],
      password: [''],
      status: ['', Validators.required],
      project_id: [null, Validators.required],
    });
  }

  ngOnInit(): void {
    this.projectSubscription = this.projectService.getProjects().subscribe(
      (response) => {
        this.projectOptions = response?.data || [];
      },
      (error) => console.error('Error al obtener las project:', error)
    );

    this.roleSubscription = this.roleService.getRoles().subscribe(
      (roles) => this.roleOptions = roles,
      (error) => console.error('Error al obtener los roles:', error)
    );

   
  }

  ngOnDestroy(): void {
    this.projectSubscription.unsubscribe();
    this.roleSubscription.unsubscribe();
    this.userSubscription.unsubscribe();
  }

  ngOnChanges(changes: SimpleChanges): void {
    if (changes['administrationUserProject']) {
      const userData = changes['administrationUserProject'].currentValue;

      if (!userData) {
        this.administrationUserProjectForm.reset({
          role: '',
          name: '',
          email: '',
          password: '',
          status: '1',
          project_id: null
          
        });
      } else {
        console.log('Datos recibidos para edición:', userData);

        const selectedRole = userData.roles?.length ? userData.roles[0].name : null;
        const selectedProject = userData.projects?.length ? userData.projects[0].id : null;

        this.administrationUserProjectForm.patchValue({
          name: userData.name || '',
          email: userData.email || '',
          password: '',
          status: userData.status !== null && userData.status !== undefined ? String(userData.status) : '1',
          project_id: selectedProject || null,
          role: selectedRole || null,
        });

        console.log('Formulario actualizado:', this.administrationUserProjectForm.value);
      }
    }
  }


  handleSubmit(): void {
    if (this.administrationUserProjectForm.invalid) {
      return;
    }

    let formData = this.administrationUserProjectForm.value;

    if (!formData.password) {
      delete formData.password;
    }

    this.onSave.emit(formData);
    this.administrationUserProjectForm.reset();
  }
}
