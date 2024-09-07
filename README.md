# Server Project

## License

This server is completely free to use. You are welcome to recreate, modify, or redistribute it, but selling this server is strictly prohibited.

## Changelog V2

- **Optimized Entire Codebase**: Improved performance and efficiency.
- **Implemented Modern PHP Security Standards**: Enhanced security with up-to-date practices.
- **Mitigated SQL Injection Risks**: Applied best practices to prevent SQL injection (efforts made to ensure safety).
- **Dropped Live Backgrounds Plan**: Removed due to performance issues on Android devices.
- **Optimized for Android Phones**: Ensured better compatibility and performance on Android devices.
- **Fixed Table Overflow Issues**: Corrected tables going outside the div container.
- **Added Multiple Mod Menu Login Support**: Supports multiple mod menus.
- **Introduced One-Device Login**: Added support for single-device login.

## PHP Files

- **`index.php`**: Handles admin login requests.
- **`panel.php`**: Manages the control panel for the server.
- **`addmenus.php`**: Allows you to add or delete mod menus.

## Folder Structure

- **`assets/`**: Contains all background images.
- **`cred/`**: Stores credentials.
- **`elements/`**: Contains server elements.

## Credentials

- **Admin Login Details**: Stored in `cred/var.php`.
- **Database Login Details**: Stored in `cred/_dbconnect.php`.
- **Database SQL Import File**: Provided with the server files.

## Client-Side Management

The `api.php` file handles all mod menu requests.

### Login Request Parameters

- **`integrityKey`**: For two-way authentication. Store this key in your mod menu as well.
- **`key`**: The login key entered by the user.
- **`modid`**: The mod menu ID to differentiate between various mod menus. Obtain this ID from the "Add Mod Menu" page.

#### Additional Parameter for One-Device Login

- **`uuid`**: Android Unique Identifier. Required for one-device login.

## Note

Make sure to customize the login menu as needed. This documentation only provides server code and setup instructions.

