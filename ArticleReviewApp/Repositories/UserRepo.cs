using ArticleReviewApp.Data;
using ArticleReviewApp.Models;
using Microsoft.EntityFrameworkCore;

namespace ArticleReviewApp.Repositories;

public class UserRepo
{
    public async Task<User?> AuthenticateAsync(string email, string password)
    {
        using var db = DbContextFactory.CreateDbContext();
        var user = await db.Users
            .AsNoTracking()
            .FirstOrDefaultAsync(u => u.Email == email);


        if (user is null || !string.Equals(user.Password, password, StringComparison.Ordinal))
        {
            return null;
        }

        return user;
    }

    public async Task<bool> AddRolesAsync(int userId, Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        var updated = await db.Users
            .Where(u => u.Id == userId)
            .ExecuteUpdateAsync(s => s.SetProperty(u => u.Roles, u => u.Roles | roles));
        return updated > 0;
    }

    public async Task<bool> RemoveRolesAsync(int userId, Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        var updated = await db.Users
            .Where(u => u.Id == userId)
            .ExecuteUpdateAsync(s => s.SetProperty(u => u.Roles, u => u.Roles & ~roles));
        return updated > 0;
    }

    public async Task<bool> SetRolesAsync(int userId, Role roles)
    {
        using var db = DbContextFactory.CreateDbContext();
        var updated = await db.Users
            .Where(u => u.Id == userId)
            .ExecuteUpdateAsync(s => s.SetProperty(u => u.Roles, roles));
        return updated > 0;
    }
}
