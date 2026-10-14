using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using CommunityToolkit.Mvvm.ComponentModel;
using ArticleReviewApp.Models;
using ArticleReviewApp.Models.Dtos;
using ArticleReviewApp.Repositories;

namespace ArticleReviewApp
{
    public class UserRolesViewModel : ObservableObject
    {
        private readonly UserRepo _userRepo = new();

        private readonly User _currentUser;


        public UserRolesViewModel(User currentUser)
        {
            _currentUser = currentUser;
        }

        public bool IsAdmin => _currentUser.Roles.HasFlag(Role.Admin);
        
    }
}