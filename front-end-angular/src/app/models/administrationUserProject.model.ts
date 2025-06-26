import { Company } from "./company.model";
import { Project } from "./project.model";
import { Role } from "./role.model";


export interface AdministrationUserProject {
    id?: number;
    role: string;
    name: string;
    email: string;
    password: string;
    status: string;
    projects?: Project[];
    roles?: Role[];
    project_id: number;
    created_at: string | null;
    updated_at: string | null;
}