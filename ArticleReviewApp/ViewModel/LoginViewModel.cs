using System.Windows.Controls;
using ArticleReviewApp.Models;
using ArticleReviewApp.Repositories;
using CommunityToolkit.Mvvm.ComponentModel;
using CommunityToolkit.Mvvm.Input;

namespace ArticleReviewApp
{
    public partial class LoginViewModel : ObservableObject
    {
        private readonly UserRepo _userRepo = new();

        [ObservableProperty]
        public partial string Email { get; set; } = string.Empty;

        [ObservableProperty]
        [NotifyPropertyChangedFor(nameof(HasError))]
        public partial string ErrorMessage { get; set; } = string.Empty;

        public bool HasError => ErrorMessage.Length > 0;

        // Set once login succeeds; read by App to start the main window.
        public User? AuthenticatedUser { get; private set; }

        // Raised on successful login so the view can close itself.
        public event EventHandler? LoginSucceeded;

        // PasswordBox.Password can't be data-bound (by design, so the password
        // isn't kept in a bindable string), so the view passes the control as
        // the command parameter instead.
        [RelayCommand]
        private async Task Login(PasswordBox passwordBox)
        {
            string email = Email.Trim();
            string password = passwordBox.Password;

            if (email.Length == 0 || password.Length == 0)
            {
                ErrorMessage = "Enter your email and password.";
                return;
            }

            User? user;
            try
            {
                user = await _userRepo.AuthenticateAsync(email, password);
            }
            catch (Exception ex)
            {
                ErrorMessage = $"Could not reach the database: {ex.Message}";
                return;
            }

            if (user is null)
            {
                ErrorMessage = "Invalid email or password.";
                passwordBox.Clear();
                return;
            }

            if (user.Roles == Role.None)
            {
                ErrorMessage = "This account has no roles assigned. Contact an administrator.";
                return;
            }

            ErrorMessage = string.Empty;
            AuthenticatedUser = user;
            LoginSucceeded?.Invoke(this, EventArgs.Empty);
        }
    }
}
