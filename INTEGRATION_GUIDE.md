# Proxmox and Ansible Integration Guide

This guide explains how to set up the Proxmox and Ansible integration for the VMDATA-TI project.

## 1. Proxmox Configuration

To enable Proxmox integration, add the following variables to your `.env` file:

```env
# PROXMOX CONFIGURATION
PROXMOX_HOST=https://your-proxmox-ip:8006
PROXMOX_USERNAME=root
PROXMOX_PASSWORD=your_password
PROXMOX_REALM=pam  # e.g., pam or pve
PROXMOX_NODE=pve   # The name of your Proxmox node
PROXMOX_STORAGE=local-lvm
```

**Verification:**
After setting these variables, navigate to **Admin > Integration** in the dashboard.
If configured correctly, you should see the Proxmox node status (green card) showing CPU/RAM usage and uptime.

## 2. Ansible Configuration

Ensure Ansible is installed on the server hosting this Laravel application.

To verify installation, run:
```bash
ansible --version
```

Configure the paths to your Ansible playbooks in `.env`:

```env
# ANSIBLE CONFIGURATION
ANSIBLE_PLAYBOOK_PATH=/path/to/your/ansible/playbooks
ANSIBLE_INVENTORY_PATH=/path/to/your/ansible/hosts
```

**Verification:**
Navigate to **Admin > Integration**.
Start by checking if the "Ansible Controller" status is green (Installed).
If it shows as "Not Found", ensure `ansible` command is in result path or install it using `pip` or system package manager.

## 3. Usage

- **Check Status:** The integration page automatically checks connection status on load.
- **Terminal Access:** Click "Open Node Console" on the integration page to open the Proxmox web console for the node.
- **VM Provisioning:** (Future Feature) The integration is prepared for automating VM creation via Proxmox API and Ansible playbooks.
