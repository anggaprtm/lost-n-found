// Lost & Found Application JavaScript
document.addEventListener("DOMContentLoaded", () => {
  // Initialize all components
  initializeDropdowns()
  initializeModals()
  initializeNotifications()
  initializeFormValidation()
  initializeImagePreviews()
  initializeSearchFilters()
  initializeTooltips()

  // Auto-hide alerts after 5 seconds
  setTimeout(() => {
    const alerts = document.querySelectorAll(".alert")
    alerts.forEach((alert) => {
      alert.style.transition = "opacity 0.5s ease-out"
      alert.style.opacity = "0"
      setTimeout(() => alert.remove(), 500)
    })
  }, 5000)
})

// Dropdown functionality
function initializeDropdowns() {
  const dropdowns = document.querySelectorAll(".dropdown")

  dropdowns.forEach((dropdown) => {
    const button = dropdown.querySelector("button")
    const menu = dropdown.querySelector(".dropdown-menu")

    if (button && menu) {
      button.addEventListener("click", (e) => {
        e.stopPropagation()

        // Close other dropdowns
        dropdowns.forEach((otherDropdown) => {
          if (otherDropdown !== dropdown) {
            otherDropdown.querySelector(".dropdown-menu")?.classList.add("hidden")
          }
        })

        // Toggle current dropdown
        menu.classList.toggle("hidden")
      })
    }
  })

  // Close dropdowns when clicking outside
  document.addEventListener("click", () => {
    dropdowns.forEach((dropdown) => {
      dropdown.querySelector(".dropdown-menu")?.classList.add("hidden")
    })
  })
}

// Modal functionality
function initializeModals() {
  const modals = document.querySelectorAll("[data-modal]")

  modals.forEach((modal) => {
    const modalId = modal.dataset.modal
    const modalElement = document.getElementById(modalId)

    if (modalElement) {
      modal.addEventListener("click", () => {
        openModal(modalId)
      })
    }
  })

  // Close modal when clicking backdrop
  document.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal-backdrop")) {
      closeModal(e.target.closest(".modal").id)
    }
  })

  // Close modal with Escape key
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      const openModal = document.querySelector(".modal:not(.hidden)")
      if (openModal) {
        closeModal(openModal.id)
      }
    }
  })
}

function openModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.classList.remove("hidden")
    document.body.style.overflow = "hidden"

    // Focus first input
    const firstInput = modal.querySelector("input, textarea, select")
    if (firstInput) {
      setTimeout(() => firstInput.focus(), 100)
    }
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId)
  if (modal) {
    modal.classList.add("hidden")
    document.body.style.overflow = ""
  }
}

// Notification system
function initializeNotifications() {
  window.showNotification = (message, type = "info", duration = 5000) => {
    const notification = document.createElement("div")
    notification.className = `notification ${type} animate-slide-in`

    const icon = getNotificationIcon(type)
    notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${icon}
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button class="inline-flex text-gray-400 hover:text-gray-600 focus:outline-none" onclick="this.parentElement.parentElement.parentElement.remove()">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `

    document.body.appendChild(notification)

    // Auto remove after duration
    setTimeout(() => {
      notification.style.opacity = "0"
      setTimeout(() => notification.remove(), 300)
    }, duration)
  }
}

function getNotificationIcon(type) {
  const icons = {
    success:
      '<svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    error:
      '<svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
    warning:
      '<svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
    info: '<svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
  }
  return icons[type] || icons.info
}

// Form validation
function initializeFormValidation() {
  const forms = document.querySelectorAll("form[data-validate]")

  forms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      if (!validateForm(form)) {
        e.preventDefault()
      }
    })

    // Real-time validation
    const inputs = form.querySelectorAll("input, textarea, select")
    inputs.forEach((input) => {
      input.addEventListener("blur", () => validateField(input))
      input.addEventListener("input", () => clearFieldError(input))
    })
  })
}

function validateForm(form) {
  let isValid = true
  const inputs = form.querySelectorAll("input[required], textarea[required], select[required]")

  inputs.forEach((input) => {
    if (!validateField(input)) {
      isValid = false
    }
  })

  return isValid
}

function validateField(field) {
  const value = field.value.trim()
  const fieldName = field.name || field.id
  let isValid = true
  let errorMessage = ""

  // Required validation
  if (field.hasAttribute("required") && !value) {
    isValid = false
    errorMessage = `${getFieldLabel(field)} wajib diisi.`
  }

  // Email validation
  if (field.type === "email" && value && !isValidEmail(value)) {
    isValid = false
    errorMessage = "Format email tidak valid."
  }

  // Minimum length validation
  const minLength = field.getAttribute("minlength")
  if (minLength && value.length < Number.parseInt(minLength)) {
    isValid = false
    errorMessage = `${getFieldLabel(field)} minimal ${minLength} karakter.`
  }

  // Password confirmation
  if (field.name === "password_confirmation") {
    const passwordField = document.querySelector('input[name="password"]')
    if (passwordField && value !== passwordField.value) {
      isValid = false
      errorMessage = "Konfirmasi password tidak sesuai."
    }
  }

  // Show/hide error
  if (isValid) {
    clearFieldError(field)
  } else {
    showFieldError(field, errorMessage)
  }

  return isValid
}

function getFieldLabel(field) {
  const label = document.querySelector(`label[for="${field.id}"]`)
  return label ? label.textContent.replace("*", "").trim() : field.name
}

function showFieldError(field, message) {
  clearFieldError(field)

  field.classList.add("border-red-500", "focus:ring-red-500")

  const errorElement = document.createElement("p")
  errorElement.className = "mt-1 text-sm text-red-600 field-error"
  errorElement.textContent = message

  field.parentNode.appendChild(errorElement)
}

function clearFieldError(field) {
  field.classList.remove("border-red-500", "focus:ring-red-500")

  const errorElement = field.parentNode.querySelector(".field-error")
  if (errorElement) {
    errorElement.remove()
  }
}

function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

// Image preview functionality
function initializeImagePreviews() {
  const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]')

  fileInputs.forEach((input) => {
    input.addEventListener("change", (e) => {
      const file = e.target.files[0]
      if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          showImagePreview(input, e.target.result)
        }
        reader.readAsDataURL(file)
      }
    })
  })
}

function showImagePreview(input, src) {
  let preview = input.parentNode.querySelector(".image-preview")

  if (!preview) {
    preview = document.createElement("div")
    preview.className = "image-preview mt-3"
    input.parentNode.appendChild(preview)
  }

  preview.innerHTML = `
        <div class="relative inline-block">
            <img src="${src}" alt="Preview" class="w-32 h-32 object-cover rounded-lg border border-gray-300">
            <button type="button" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600" onclick="removeImagePreview(this)">
                ×
            </button>
        </div>
    `
}

function removeImagePreview(button) {
  const preview = button.closest(".image-preview")
  const input = preview.parentNode.querySelector('input[type="file"]')

  preview.remove()
  input.value = ""
}

// Search and filter functionality
function initializeSearchFilters() {
  const searchInputs = document.querySelectorAll("input[data-search]")

  searchInputs.forEach((input) => {
    let timeout
    input.addEventListener("input", (e) => {
      clearTimeout(timeout)
      timeout = setTimeout(() => {
        performSearch(e.target)
      }, 300)
    })
  })
}

function performSearch(input) {
  const searchTerm = input.value.toLowerCase()
  const targetSelector = input.dataset.search
  const targets = document.querySelectorAll(targetSelector)

  targets.forEach((target) => {
    const text = target.textContent.toLowerCase()
    const shouldShow = text.includes(searchTerm)

    target.style.display = shouldShow ? "" : "none"
  })
}

// Tooltip functionality
function initializeTooltips() {
  const tooltipElements = document.querySelectorAll("[data-tooltip]")

  tooltipElements.forEach((element) => {
    element.addEventListener("mouseenter", showTooltip)
    element.addEventListener("mouseleave", hideTooltip)
  })
}

function showTooltip(e) {
  const element = e.target
  const tooltipText = element.dataset.tooltip

  if (!tooltipText) return

  const tooltip = document.createElement("div")
  tooltip.className = "tooltip-popup absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded whitespace-nowrap"
  tooltip.textContent = tooltipText

  document.body.appendChild(tooltip)

  const rect = element.getBoundingClientRect()
  tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + "px"
  tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + "px"

  element._tooltip = tooltip
}

function hideTooltip(e) {
  const element = e.target
  if (element._tooltip) {
    element._tooltip.remove()
    delete element._tooltip
  }
}

// Utility functions
window.confirmDelete = (message = "Apakah Anda yakin ingin menghapus item ini?") => confirm(message)

window.copyToClipboard = (text) => {
  navigator.clipboard
    .writeText(text)
    .then(() => {
      window.showNotification("Teks berhasil disalin!", "success")
    })
    .catch(() => {
      window.showNotification("Gagal menyalin teks.", "error")
    })
}

window.formatCurrency = (amount) =>
  new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
  }).format(amount)

window.formatDate = (date, options = {}) => {
  const defaultOptions = {
    year: "numeric",
    month: "long",
    day: "numeric",
  }

  return new Intl.DateTimeFormat("id-ID", { ...defaultOptions, ...options }).format(new Date(date))
}

// Loading states
window.showLoading = (element) => {
  const originalContent = element.innerHTML
  element.dataset.originalContent = originalContent
  element.innerHTML = '<span class="spinner mr-2"></span>Loading...'
  element.disabled = true
}

window.hideLoading = (element) => {
  element.innerHTML = element.dataset.originalContent || element.innerHTML
  element.disabled = false
  delete element.dataset.originalContent
}

// Auto-resize textareas
document.addEventListener("input", (e) => {
  if (e.target.tagName === "TEXTAREA" && e.target.hasAttribute("data-auto-resize")) {
    e.target.style.height = "auto"
    e.target.style.height = e.target.scrollHeight + "px"
  }
})

// Smooth scrolling for anchor links
document.addEventListener("click", (e) => {
  if (e.target.tagName === "A" && e.target.getAttribute("href")?.startsWith("#")) {
    e.preventDefault()
    const targetId = e.target.getAttribute("href").substring(1)
    const targetElement = document.getElementById(targetId)

    if (targetElement) {
      targetElement.scrollIntoView({
        behavior: "smooth",
        block: "start",
      })
    }
  }
})

// Print functionality
window.printPage = () => {
  window.print()
}

// Export functionality
window.exportTable = (tableId, filename = "export.csv") => {
  const table = document.getElementById(tableId)
  if (!table) return

  const csv = []
  const rows = table.querySelectorAll("tr")

  rows.forEach((row) => {
    const cols = row.querySelectorAll("td, th")
    const rowData = Array.from(cols).map((col) => `"${col.textContent.trim()}"`)
    csv.push(rowData.join(","))
  })

  const csvContent = csv.join("\n")
  const blob = new Blob([csvContent], { type: "text/csv" })
  const url = window.URL.createObjectURL(blob)

  const a = document.createElement("a")
  a.href = url
  a.download = filename
  a.click()

  window.URL.revokeObjectURL(url)
}

// Theme toggle (if needed)
window.toggleTheme = () => {
  document.body.classList.toggle("dark-mode")
  localStorage.setItem("theme", document.body.classList.contains("dark-mode") ? "dark" : "light")
}

// Initialize theme from localStorage
if (localStorage.getItem("theme") === "dark") {
  document.body.classList.add("dark-mode")
}

// Performance optimization: Lazy loading for images
if ("IntersectionObserver" in window) {
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        const img = entry.target
        img.src = img.dataset.src
        img.classList.remove("lazy")
        observer.unobserve(img)
      }
    })
  })

  document.querySelectorAll("img[data-src]").forEach((img) => {
    imageObserver.observe(img)
  })
}

// Service Worker registration (for PWA capabilities)
if ("serviceWorker" in navigator) {
  window.addEventListener("load", () => {
    navigator.serviceWorker
      .register("/sw.js")
      .then((registration) => {
        console.log("SW registered: ", registration)
      })
      .catch((registrationError) => {
        console.log("SW registration failed: ", registrationError)
      })
  })
}
