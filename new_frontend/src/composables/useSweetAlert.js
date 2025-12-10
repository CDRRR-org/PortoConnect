import Swal from 'sweetalert2'

export function useSweetAlert() {
  const showSuccess = (message) => {
    return Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: message,
      confirmButtonColor: '#7c3aed',
      confirmButtonText: 'OK'
    })
  }

  const showError = (message) => {
    return Swal.fire({
      icon: 'error',
      title: 'Error!',
      text: message,
      confirmButtonColor: '#dc2626',
      confirmButtonText: 'OK'
    })
  }

  const showWarning = (message) => {
    return Swal.fire({
      icon: 'warning',
      title: 'Peringatan!',
      text: message,
      confirmButtonColor: '#f59e0b',
      confirmButtonText: 'OK'
    })
  }

  const showInfo = (message) => {
    return Swal.fire({
      icon: 'info',
      title: 'Informasi',
      text: message,
      confirmButtonColor: '#3b82f6',
      confirmButtonText: 'OK'
    })
  }

  const showConfirm = (message, confirmText = 'Ya', cancelText = 'Tidak') => {
    return Swal.fire({
      title: 'Konfirmasi',
      text: message,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#7c3aed',
      cancelButtonColor: '#6b7280',
      confirmButtonText: confirmText,
      cancelButtonText: cancelText
    })
  }

  return {
    showSuccess,
    showError,
    showWarning,
    showInfo,
    showConfirm
  }
}


