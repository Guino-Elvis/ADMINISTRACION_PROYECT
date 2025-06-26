import { Injectable } from '@angular/core';
import { API_ENDPOINTS } from './api/api.endpoints';
import { ApiService } from './api.service';
import { HotToastService } from '@ngxpert/hot-toast';
import { catchError, Observable, tap } from 'rxjs';
import { AdministrationUserProject } from '../models/administrationUserProject.model';

@Injectable({
  providedIn: 'root'
})
export class UserProjectService {
private endpoint = API_ENDPOINTS.USER_PROJECTS;
  constructor(private apiService: ApiService, private toast: HotToastService) { }

  getAdministrationUserProjects(
    search: string = '',
    startDate: string | null = null,
    endDate: string | null = null,
    sortOrder: 'asc' | 'desc' = 'desc',
    perPage: number = 10,
    page: number = 1
  ): Observable<any> {
    const params: string[] = [];

    if (search.trim()) {
      params.push(`search=${encodeURIComponent(search)}`);
    }

    if (startDate) {
      params.push(`start_date=${encodeURIComponent(startDate)}`);
    }

    if (endDate) {
      params.push(`end_date=${encodeURIComponent(endDate)}`);
    }

    params.push(`sort_order=${sortOrder}`);
    params.push(`per_page=${perPage}`);
    params.push(`page=${page}`);

    const queryString = params.length ? `?${params.join('&')}` : '';
    const url = `${this.endpoint}${queryString}`;

    return this.apiService.get(url).pipe(
      tap(() => this.toast.success('Usuarios obtenidos con éxito',{
        position: 'bottom-right',
      })),
      catchError((error) => {
        this.toast.error('Error al obtener los usuarios',{
          position: 'bottom-right',
        });
        throw error;
      })
    );
  }


  createAdministrationUserProject(administrationUserProject: AdministrationUserProject): Observable<AdministrationUserProject> {
    return this.apiService.post<AdministrationUserProject>(this.endpoint, administrationUserProject).pipe(
      tap(() => this.toast.success('Usuario creado con éxito',{
        position: 'bottom-right',
      })),
      catchError((error) => {
        this.toast.error('Error al crear el usuario',{
          position: 'bottom-right',
        });
        throw error;
      })
    );
  }

  updateAdministrationUserProject(id: number, administrationUserProject: AdministrationUserProject): Observable<AdministrationUserProject> {
    return this.apiService.put<AdministrationUserProject>(this.endpoint,id, administrationUserProject).pipe(
      tap(() => this.toast.success('Usuario actualizado con éxito',{
        position: 'bottom-right',
      })),
      catchError((error) => {
        this.toast.error('Error al actualizar el usuario',{
          position: 'bottom-right',
        });
        throw error;
      })
    );
  }

  deleteAdministrationUserProject(id: number): Observable<void> {
    return this.apiService.delete<void>(this.endpoint, id).pipe(
      tap(() => this.toast.success('Usuario eliminado con éxito',{
        position: 'bottom-right',
      })),
      catchError((error) => {
        this.toast.error('Error al eliminar el usuario',{
          position: 'bottom-right',
        });
        throw error;
      })
    );
  }

}
