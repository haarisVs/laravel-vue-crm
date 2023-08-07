import {
  mdiDesktopMac,
  mdiSquareEditOutline,
  mdiTable,
  mdiLock,
  mdiFileDocumentOutline, mdiAccount, mdiPen, mdiAccessPoint
} from '@mdi/js'

const externalLink = () => {
  return process.env.NODE_ENV === 'production' ? window.location.origin + '/api-docs' : 'http://localhost:8080/api-docs';
}

export default [
  'System',
  [
    {
      to: '/',
      label: 'Dashboard',
      icon: mdiDesktopMac
    }
  ],
  'Modules',
  [
  {
      to: '/users',
      label: 'Users',
      icon: mdiAccount,
      component: () => import('@/views/CRUD/Users/UsersView.vue'),
  },
  {
    to: '/companies',
    label: 'Companies',
    icon: mdiAccount,
    component: () => import('@/views/CRUD/Companies/CompaniesView.vue'),
  },
  {
    to: '/employees',
    label: 'Employees',
    icon: mdiAccount,
    component: () => import('@/views/CRUD/Employees/EmployeesView.vue'),
  },
  {
    to: '/change_password',
    label: 'Password Rest',
    icon: mdiLock,
    component: () => import('@/views/ChangePasswordView.vue'),
  },
  ],
]
