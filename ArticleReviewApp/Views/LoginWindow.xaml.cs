using System.Windows;

namespace ArticleReviewApp;


public partial class LoginWindow : Window
{
    public LoginWindow(LoginViewModel vm)
    {
        DataContext = vm;
        InitializeComponent();

        vm.LoginSucceeded += (_, _) => DialogResult = true;
    }
}
