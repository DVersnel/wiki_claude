using System.Windows;

namespace ArticleReviewApp;


public partial class LoginWindow : Window
{
    public LoginWindow()
    {
        var vm = new MainWindowViewModel();

        InitializeComponent();
    }
}