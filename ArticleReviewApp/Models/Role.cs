namespace ArticleReviewApp.Models;

[Flags]
public enum Role
{
    None     = 0,
    Reader   = 1 << 0, // 1
    Reviewer = 1 << 1, // 2
    Editor   = 1 << 2, // 4
    Admin    = 1 << 3, // 8

    All = Reader | Reviewer | Editor | Admin
}
