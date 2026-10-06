using ArticleReviewApp.Data;
using ArticleReviewApp.Models;
using Microsoft.EntityFrameworkCore;

namespace ArticleReviewApp.Repositories;

public class UserRepo
{
    // Returns the user when the email/password pair is valid, otherwise null.
    public async Task<User?> AuthenticateAsync(string email, string password)
    {
        using var db = DbContextFactory.CreateDbContext();
        var user = await db.Users
            .AsNoTracking()
            .FirstOrDefaultAsync(u => u.Email == email);

        // Compared in C# rather than SQL: SQL Server's default collation is
        // case-insensitive, which would make "PASSWORD123" match "password123".
        if (user is null || !string.Equals(user.Password, password, StringComparison.Ordinal))
        {
            return null;
        }

        return user;
    }
}
